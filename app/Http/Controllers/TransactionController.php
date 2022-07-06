<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\TripayController;

class TransactionController extends Controller
{
    public function show(Request $request)
    {
        if(!$request->reference) return response([ 'success' => false ],404);
        
        $data = null;
        $status = 400;       
        
        $ref= $request->reference;

        $tripay = new TripayController();
        if(Cache::get('transaction_' . $ref)) {
            $data = Cache::get('transaction_' . $ref);
            $status = 200;
        } else {

            $response = $tripay->getTransactionDetail($ref);
            if($response) {
                Cache::put('transaction_' . $ref, $response ,now()->addDays(2));
                $data= $response;
                $status = 200;
            }
        }

        return response()->json($data, $status);
    }

}
