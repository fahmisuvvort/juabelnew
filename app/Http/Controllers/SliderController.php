<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class SliderController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response([
            'success' => true,
            'results' =>  Slider::OrderBy('weight', 'asc')->get()
        ], 200);
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
            'image' => ['required']
        ]);
        DB::beginTransaction();

        try {
            $path = public_path('/upload/images');
    
            if(! File::exists($path)) {
                File::makeDirectory($path, 0755, true, true);
            }
            $file = $request->file('image');
    
            $filename = Str::random(38).'.' . $file->extension();
    
            $file->move($path, $filename);
    
            $last = Slider::OrderBy('weight', 'desc')->first();
    
            Slider::create([
                'filename' => $filename,
                'weight' => $last? $last->weight+1 : 1
            ]);

            DB::commit();
    
            return response([
                'success' => true,
                'message' => 'Berhasil menambah item',
                'results' => null
            ], 200);

        } catch (\Throwable $th) {
            
            DB::rollBack();

            return response([
                'success' => true,
                'message' => 'Gagal menambah item, silahkan ulangi lagi',
                'results' => null
            ], 500);
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
        $slider = Slider::find($id);

        File::delete('upload/images/'. $slider->filename);

        $slider->delete();

        return response([
            'success' => true, 
            'message' => 'Berhasil menghapus item',
        ], 200);
    }
    /**
     * Update slider weight field.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateSliderWeight(Request $request)
    {
        $slider = Slider::find($request->id);
        $slider->weight = (int)$request->value;
        $slider->save();

        return response([
            'success' => true, 
            'message' => 'Berhasil memperbarui item',
        ], 200);
    }
}
