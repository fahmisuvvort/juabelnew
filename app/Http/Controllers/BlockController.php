<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class BlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'results' => Block::with('post:id,slug,title')->orderBy('position', 'desc')->get()
        ]);
    }
    public function show($id)
    {
        return response()->json([
            'success' => true,
            'results' => Block::find($id)->load('post')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'label' => ['required'],
            'image' => ['required'],
            'position' => ['required'],
        ]);

        $path = public_path('/upload/images');

        if(! File::exists($path)) {
            File::makeDirectory($path, 0755, true, true);
        }

        DB::beginTransaction();

        try {
            $block = new Block();
    
            $block->label = $request->label;
            $block->description = $request->description;
            $block->position = $request->position;
            $block->weight = $request->weight;
            
            if($request->post_id) {
                $block->post_id = $request->post_id;
            } 

            if($file = $request->file('image')) {
                $filename = Str::random(42).'.' . $file->extension();
        
                if($file->move($path, $filename)){
    
                    $block->image = $filename;
    
                }
            }
        
            $block->save();

            DB::commit();

            Cache::forget('blocks');

            return response(['success' => true], 201);

        } catch (\Throwable $th) {
            //throw $th;
            return response([
                'success' => false
            ], 400);
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'label' => ['required'],
        ]);

        $path = public_path('/upload/images');

        DB::beginTransaction();

        try {
            $block = Block::findOrFail($request->id);
    
            $block->label = $request->label;
            $block->description = $request->description;
            $block->position = $request->position;
            $block->weight = $request->weight;

            if($request->post_id) {
                $block->post_id = $request->post_id;
            }  else {
                $block->post_id = null;
            }
            if($file = $request->file('image')) {
                $filename = Str::random(42).'.' . $file->extension();

                if($block->image){
                    File::delete('upload/images/'. $block->image);
                }
      
                if($file->move($path, $filename)){
    
                   $block->image = $filename;
    
                }
            }
        
            $block->save();

            DB::commit();

            Cache::forget('blocks');

            return response(['success' => true], 200);

        } catch (\Throwable $th) {
            //throw $th;

            DB::rollBack();
            return response(['success' => false], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $block = Block::find($id);

        DB::beginTransaction();

        try {
            if($block->image) {
                File::delete('upload/images/'. $block->image);
            }
            $block->delete();

            DB::commit();

            Cache::forget('blocks');

            return response(['success' => true, 'message' => 'Berhasil menghapus data'], 200);


        } catch (\Throwable $th) {
            DB::rollBack();

            return response(['success' => false, 'message' => 'Gagal menghapus data'], 400);
        }
    }
}
