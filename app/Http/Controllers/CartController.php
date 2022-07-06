<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function get($sessId)
    {
        if(!$sessId) {
            return response(['status' => false], 500);
        }

        return response()->json([
            'results' => Cart::where('session_id', $sessId)->get()
        ], 200);
    }

    public function store(Request $request, $sessId)
    {
        if(!$sessId) {
            return response(['status' => false], 500);
        }

        $request->validate([
            'session_id' => 'required',
            'price' => 'required|numeric',
            'name' => 'required',
            'quantity' => 'required|numeric',
            'weight' => 'required|numeric',
            'sku' => 'required',
            'image_url' => 'required',
            'product_url' => 'required',
            'product_stock' => 'required',
        ]);

        $cart = Cart::where('session_id', $sessId)->where('sku', $request->sku)->first();

        if($cart) {
            $cart->quantity += $request->quantity;
            $cart->save();
            
        } else {

            Cart::create([
                'session_id' => $request->session_id,
                'price' => $request->price,
                'name' => $request->name,
                'weight' => $request->weight,
                'quantity' => $request->quantity,
                'sku' => $request->sku,
                'image_url' => $request->image_url,
                'product_url' => $request->product_url,
                'product_stock' => $request->product_stock,
                'product_id' => $request->product_id,
                'note' => $request->note,
                'seller' => $request->seller
            ]);
        }


        return response(['status' => true], 200);
    }

    public function update(Request $request, $sessId)
    {
        if(!$sessId) {
            return response([
                'status' => false
            ], 500);
        }

        $cart = Cart::where('session_id', $sessId)->where('sku', $request->sku)->first();

        if($cart) {
            
            $cart->quantity = $request->quantity;
    
            $cart->save();
    
            return response(['status' => true], 200);
        }

        return response(['status' => false], 400);
    }

    public function destroy(Request $request)
    {
        Cart::where('sku', $request->sku)->where('session_id', $request->session_id)->delete();

        Cart::where('created_at', '<', Carbon::now()->subDays(3))->delete();

        return response(['status' => true], 200);
    }
    public function clear(Request $request)
    {
        Cart::where('session_id', $request->session_id)->delete();

        return response(['status' => true], 200);
    }
}
