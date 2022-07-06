<?php

namespace App\Http\Controllers;

use Rajaongkir;
use App\Models\City;
use App\Models\Config;
use App\Models\Province;
use App\Models\Subdistrict;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ShippingController extends Controller
{
    public function getProvince()
    {
        if(Cache::has('provinces')) {

            return response()->json([
                'success' => true,
                'results' =>  Cache::get('provinces')
            ]);

        } else {

            $data = Province::all();
            Cache::forever('provinces', $data);

            return response()->json([
                'success' => true,
                'results' => $data
            ]);


        }

    }
    
    public function getCity($province_id)
    {


        if(Cache::has('city_by_'. $province_id)) {

           return response()->json([
                'success' => true,
                'results' => Cache::get('city_by_'. $province_id)
            ]);

        } else { 
            
            $data = City::where('province_id', $province_id)->get();
            Cache::forever('city_by_'. $province_id, $data);

            return response()->json([
                'success' => true,
                'results' => $data
            ]);

        }    

    }
    public function getSubdistrict($city_id)
    {


        if(Cache::has('subdistrict_by_'. $city_id)) {

            return response()->json([
                'success' => true,
                'results' => Cache::get('subdistrict_by_'. $city_id)
            ]);

        } else { 

            $data = Subdistrict::where('city_id', $city_id)->get();
            Cache::forever('subdistrict_by_'. $city_id, $data);

            return response()->json([
                'success' => true,
                'results' => $data
            ]);
        
        }

    }
    public function getCost(Request $request)
    {
        $data = $request->validate([
            "origin"        => 'required',
            "destination"   => 'required',
            "weight"        => 'required',
            "courier"       => 'required',
        ]);
        
        $key = http_build_query($data);

        if(Cache::has($key)) {

            return response()->json([
                'success' => true,
                'results' => Cache::get($key)
            ]);

        } else {
   
            $json = Rajaongkir::cost($request->all());
    
            $obj = json_decode($json);
    
            if($obj->success == true && count($obj->results) > 0) {
    
                Cache::put($key, $obj->results, now()->addHours(8));

                return response()->json($obj);
    
            } else {
    
                return response()->json([
                    'success' => false,
                    'message' => $obj->message
                ]);
            }

        }
    }
    public function waybill(Request $request)
    {
        $data = $request->validate([
            'courier' => 'required',
            'waybill' => 'required',
        ]);

        $key = http_build_query($data);

        if(Cache::has($key)) {

            return response()->json([
                'success' => true,
                'results' => Cache::get($key)
            ]);

        } else {

            $json = Rajaongkir::waybill($data);

            $obj = json_decode($json);
        
            if($obj->success == true && count($obj->results) > 0) {

                Cache::put($key, $obj->results, now()->addHours(2));

                return response()->json($obj);

            } else {

                return response()->json([
                    'success' => false,
                    'message' => $obj->message
                ]);
            }
        }

    }

    public function findSubdistrict($key)
    {
        $key = filter_var($key, FILTER_SANITIZE_SPECIAL_CHARS);
        
        $subdistricts = Subdistrict::where('subdistrict_name', 'like', $key.'%')
            ->get();

        return response()->json([
            'success' => true,
            'results' => $subdistricts
        ]);
    }
}
