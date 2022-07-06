<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promote;
use App\Models\ProductPromo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PromoteController extends Controller
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
            'results' => Promote::with('discount')          
                ->withCount('products')
                ->get()
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
            'discount_id' => 'required',
            'label' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|date',
        ]);

        $promote = Promote::create([
            'discount_id' => $request->discount_id,
            'label' => $request->label,
            'start_date' => Carbon::parse($request->start_date),
            'end_date' => Carbon::parse($request->end_date),
        ]);
        return response()->json([
            'success' => true,
            'results' => $promote
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json([
            'success' => true,
            'results' => Promote::findOrFail($id)
        ]);
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
            'discount_id' => 'required',
            'label' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|date',
        ]);

        $promote = Promote::findOrFail($id);

        $promote->update([
            'discount_id' => $request->discount_id,
            'label' => $request->label,
            'start_date' => Carbon::parse($request->start_date),
            'end_date' => Carbon::parse($request->end_date),
        ]);
        return response()->json([
            'success' => true,
            'results' => $promote->fresh()
        ]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $promote = Promote::findOrFail($id);

        $promote->delete();

        return response([ 'success' => true ], 200);

    }

    public function getPromoDetail($id)
    {
        $promo = Promote::find($id);

        return response()->json([
            'success' => true,
            'results' => [
                'promoted' => $promo->load('discount'),
                'products' => Product::withSum('variantItems', 'item_stock')
                ->where('promote_id', $promo->id)
                ->get(),
            ]
        ]);

    }
    
}
