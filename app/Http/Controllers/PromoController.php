<?php

namespace App\Http\Controllers;

use App\Models\Promo;
use App\Models\ProductPromo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class PromoController extends Controller
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
            'results' => Promo::withCount('products')->latest()->get()
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
            'label' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|date',
        ]);

        $promo = Promo::create([
            'label' => $request->label,
            'start_date' => Carbon::parse($request->start_date),
            'end_date' => Carbon::parse($request->end_date),
        ]);

        Cache::forget('product_promo');
        return response()->json([
            'success' => true,
            'results' => $promo
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
            'results' => Promo::find($id)
        ]);

    }

    public function getPromoDetail($id)
    {
        $promo = Promo::findOrFail($id);

        return response()->json([
            'success' => true,
            'results' => [
                'promo' => $promo,
                'products' => $promo->products
            ]
        ]);
    }
    public function removeProductPromo(Request $request)  
    {
        $request->validate([
            'product_id' => 'required',
            'promo_id' => 'required',
        ]);

        Cache::forget('product_promo');

        ProductPromo::where('promo_id', $request->promo_id)->where('product_id', $request->product_id)->delete();

        return response()->json(['success' => true ]);
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
            'label' => 'required',
            'start_date' => 'required',
            'end_date' => 'required|date',
        ]);

        $promo = Promo::findOrFail($id);

        $promo->update([
            'label' => $request->label,
            'start_date' => Carbon::parse($request->start_date),
            'end_date' => Carbon::parse($request->end_date),
        ]);
        Cache::forget('product_promo');
        return response()->json([
            'success' => true,
            'results' => $promo->fresh()
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
        $promo = Promo::findOrFail($id);
        Cache::forget('product_promo');

        $promo->delete();

        return response([ 'success' => true ], 200);

    }

    
}
