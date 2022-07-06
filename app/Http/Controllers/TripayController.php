<?php

namespace App\Http\Controllers;

use Tripay;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

use App\Models\ProductVarian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class TripayController extends Controller
{
    protected $privateKey;

    public function __construct()
    {
        $this->apiKey = config('tripay.api_key');
        $this->privateKey = config('tripay.private_key');
        
    }
    public function getPaymentChanels()
    {
        
        if(Cache::has('tripay_payment_chanels')) {

            return response()->json([
                'success' => true,
                'data' => Cache::get('tripay_payment_chanels')
            ]);
            
            
        } else {
            
            $json = Tripay::paymentChanels();

            $obj = json_decode($json);
    
            if($obj->success == true) {

                Cache::put('tripay_payment_chanels', $obj->data, now()->addHours(6));

                return response()->json($obj);

            } else {

                return response()->json([
                    'success' => false,
                    'message' => $obj->message
                ]);
            }

        }

    }

    public function getTransactionDetail($ref)
    {
        $payload = [
            'reference' => $ref
          ];
      
        $json = Tripay::transactionDetail($payload);

        $obj = json_decode($json);

        if($obj->success == true) {

            return response()->json($obj);

        } else {

            return response()->json([
                'success' => false,
                'message' => $obj->message
            ]);
        }

    }

    public function calculatorFee(Request $request)
    {
        $payload = $request->validate([
            'code' => 'required',
            'amount' => 'required'
        ]);

        $json = Tripay::calculatorFee($payload);

        $obj = json_decode($json);

        if($obj->success == true) {

            return response()->json($obj);

        } else {

            return response()->json([
                'success' => false,
                'message' => $obj->message
            ]);
        }

    }
    public function callback(Request $request)
    {
 
        $callbackSignature = $request->server('HTTP_X_CALLBACK_SIGNATURE') ?? '';

        $json = $request->getContent();

        $data = json_decode($json);
        

        $signature = hash_hmac('sha256', $json, $this->privateKey);

        if ($signature !== (string) $callbackSignature) {
            return 'Invalid signature';
        }

        if ('payment_status' !== (string) $request->server('HTTP_X_CALLBACK_EVENT')) {
            return 'Invalid callback event, no action was taken';
        }

        $data = json_decode($json);
        $status = strtoupper((string) $data->status);
            
        $merchantRef = $data->merchant_ref;
        
        $order = Order::where('order_ref', $merchantRef)
            ->where('order_status', 'UNPAID')
            ->first();
        
        
        if( !$order ) {
            
            return "Invoice not found or current status is not UNPAID";
        }

        $transaction = $order->transaction;

        if ((int) $data->total_amount !== (int) $order->order_total + (int) $order->payment_fee) {
            return 'Invalid amount, Expected: ' . $order->order_total . ' - Received: ' . $data->total_amount;
        }

        switch ($status) {
            case 'PAID':
                $order->update([
                    'order_status'	=> 'PAID',
                ]);
                
                $transaction->update([
                    'status' => 'PAID',
                    'paid_at' => Carbon::createFromTimestamp($data->paid_at),
                    'note' => $data->note
                ]);
    
    
                return response()->json(['success' => true ]);

            case 'EXPIRED':
                $order->update([
                    'order_status'	=> 'CANCELED',
                ]);
    
                $transaction->update([
                    'status' => 'CANCELED',
                    'note' => $data->note
                ]);
    
                $this->resetStock($order);
    
    
                return response()->json(['success' => true ]);

            case 'FAILED':
                $order->update([
                    'order_status'	=> 'CANCELED',
                ]);
    
                $transaction->update([
                    'status' => 'CANCELED',
                    'note' => $data->note
                ]);
    
                $this->resetStock($order);
    
    
                return response()->json(['success' => true ]);

            default:
                return response()->json(['error' => 'Unrecognized payment status']);
        }

    }

    protected function resetStock($order) 
    {
        foreach($order->items as $item) {

            $productData = Product::where('sku', $item->sku)->first();
            if($productData) {            
                $productData->stock -= $item->quantity;
                $productData->save();
    
            } else {
    
                $variantData = ProductVarian::where('sku', $item->sku)->first();
                if($variantData) {
                    $productData->stock -= $item->quantity;
                    $variantData->save();
                }
            }
        }
    }

}
