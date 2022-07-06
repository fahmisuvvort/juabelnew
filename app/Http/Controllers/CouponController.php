<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Exception;

class CouponController extends Controller
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
            'results' => Coupon::with('discount')->get()
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
        $data = $request->validate([
            'discount_id' => 'required',
            'label' => 'required',
            'code' => 'required',
        ]);

        $coupon = Coupon::create($data);
        return response()->json([
            'success' => true,
            'results' => $coupon
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
            'results' => Coupon::find($id)
        ]);
    }
    public function getByCode($code)
    {
        $code = htmlentities($code);

        return response()->json([
            'success' => true,
            'results' => Coupon::where('code', $code)->firstOrFail()
        ]);
    }
    public function redeemCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        $result = ['success' => true, 'code' => 200];

        try {

            $coupon = Coupon::with('discount')->where('code', trim($request->code))->first();

            if(!$coupon) {
                throw new Exception('Kode kupon salah');
            }

            $result['results'] = $coupon;

        } catch (Exception $e) {

            //throw $th;
            $result = [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => 404
            ];
        }
        return response()->json($result, $result['code']);
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
         $data = $request->validate([
            'discount_id' => 'required',
            'label' => 'required',
            'code' => 'required',
        ]);
        $coupon = Coupon::findOrFail($id);

        $coupon->update($data);
        
        return response()->json([
            'success' => true,
            'results' => $coupon->fresh()
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
        $coupon = Coupon::findOrFail($id);

        $coupon->delete();

        return response()->json([
            'success' => true,
        ]);

    }
}
