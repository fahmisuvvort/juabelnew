<?php

namespace App\Http\Controllers;

use Tripay;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Config;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\ProductVarian;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\NullHandler;

class OrderController extends Controller
{
    protected $config;
    public function __construct()
    {
        $this->config =  Config::first();
    }
    public function index(Request $request)
    {
        return response([
            'success' => true,
            'results' => $this->getOrders($request)
        ]);
    }
    public function getCustomerOrders(Request $request)
    {
        $take = $request->take ?? 4;

        $user = $request->user();

        return response([
            'success' => true,
            'results' => $request->skip 
            ? Order::with('transaction')
            ->latest()
            ->where('user_id', $user->id)
            ->skip($request->skip)
            ->take($take)
            ->get()
            : Order::with('transaction')
            ->where('user_id', $user->id)
            ->latest()
            ->take($take)
            ->get()
        ]);
    }

    public function store(Request $request)
    {

        $user = null;

        if($request->user_id) {

            $user = User::find($request->user_id);
        }

        $request->validate([
            'customer_name' => ['required', 'string'],
            'customer_phone' => ['required', 'string'],
            'customer_email' => ['required', 'email'],
            'payment_method' => ['required', 'string'],
            'payment_type' => ['required', 'string'],
            'payment_name' => ['required', 'string'],
            'address' => ['required', 'string'],
            'items' => ['required', 'array'],
            'quantity' => ['required', 'numeric'],
            'subtotal' => ['required', 'numeric'],
            'total' => ['required', 'numeric'],
        ]);

        $name = filter_var($request->customer_name, FILTER_SANITIZE_SPECIAL_CHARS);
        $whatsapp = filter_var($request->customer_phone, FILTER_SANITIZE_SPECIAL_CHARS);

        $uniqueCode = rand(56, 259);
        $orderRef = 'INV-' . rand(208, 5909). Str::upper(Str::random(5));

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user? $user->id : null,
                'order_ref' => $orderRef,
                'customer_name' => $name,
                'customer_whatsapp' => $whatsapp,
                'customer_email' => $request->customer_email,
                'shipping_address' => $request->address,
                'order_qty' => $request->quantity,
                'order_weight' => $request->weight,
                'order_unique_code' => $request->payment_type == 'DIRECT' ? $uniqueCode : 0,
                'order_subtotal' => $request->subtotal,
                'order_total' => $request->payment_type == 'DIRECT' ? $request->total-$uniqueCode : $request->total,
                'order_status' => 'UNPAID',
                'shipping_courier_name' => $request->shipping_courier_name,
                'shipping_courier_service' => $request->shipping_courier_service,
                'shipping_cost' => $request->shipping_cost,
                'discount' => $request->coupon_discount,
                'payment_fee' => $request->payment_fee,
                'seller_id' => $request->seller_id ?? NULL
            ]);
                
            foreach($request->items as $item) {

                $item = $order->items()->create([
                    'sku' => $item['sku'],
                    'name' => $item['name'],
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'note' => $item['note']
                ]);

                $this->setStock($item->sku, $item->quantity, true);

            }

            if($request->payment_type == 'BANK_TRANSFER' || $request->payment_type == 'COD') {
  
                $transaction = new Transaction();
    
                $transaction->order_id = $order->id;
                $transaction->payment_type = $request->payment_type;
                $transaction->payment_method = $request->payment_method;
                $transaction->payment_code = $request->payment_code;
                $transaction->payment_name = $request->payment_name;
                $transaction->amount = $order->order_total;
    
                $transaction->payment_ref = 'DTR-' .Str::upper(Str::random(8)) . $order->id;
                $transaction->expired_time = Carbon::now()->addDays(2)->timestamp;;
                $transaction->total_fee = 0;
                $transaction->amount_received = 0;
    
                $transaction->save();

                DB::commit();
    
                return response([
                    'success' => true,
                    'results' => $order->load('items','transaction')
                ], 200);

            } else {

                $payload = [
                    'method'            => $request->payment_method,
                    'merchant_ref'      => $order->order_ref,
                    'amount'            => $order->order_total,
                    'customer_name'     => $order->customer_name,
                    'customer_email'    => $order->customer_email,
                    'customer_phone'    => $order->customer_whatsapp,
                    'order_items'       => $request->items,
                ];

                $json = Tripay::createTransaction($payload);

                $obj = json_decode($json);


                if($obj->success) {

                    $transaction = new Transaction();

                    $transaction->order_id = $order->id;
                    $transaction->payment_type = $request->payment_type;
                    $transaction->payment_name = $request->payment_name;
                    $transaction->payment_method = $request->payment_method;
    
                    $transaction->qr_url = $obj->data->qr_url ?? '';
                    
                    $transaction->payment_code = $obj->data->pay_code ?? '';
                    $transaction->payment_ref = $obj->data->reference;
                    $transaction->expired_time = $obj->data->expired_time;

                    $transaction->amount = $obj->data->amount;
                    $transaction->amount_received = $obj->data->amount_received;
                    $transaction->total_fee = $obj->data->total_fee;
                    $transaction->fee_merchant = $obj->data->fee_merchant;
                    $transaction->fee_customer = $obj->data->fee_customer;
                    $transaction->instructions = json_encode($obj->data->instructions);

                    $transaction->save();

                    $order->fresh();

                    $order->payment_fee = $obj->data->fee_customer;

                    $order->save();

                    DB::commit();

                    return response([
                        'success' => true,
                        'results' => $order->load('transaction', 'items'),
                    ], 200);
                        

                } else {
                    DB::rollBack();

                    return response([
                        'success' => false,
                        'results' => null
                    ], 400);
                }
            }

        } catch (\Throwable $th) {

            DB::rollBack();

            return response([
                'success' => false,
                'results' => null,
                'error' => $th->getMessage()
            ], 400);
        }        
        
    }

    public function show($orderRef)
    {
        return response([
            'success' => true,
            'results' => Order::with(['items', 'transaction'])->where('order_ref', $orderRef)->first()
        ]);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        $order->delete();

        return response([ 'success' => true ], 200);

    }
    public function paymentAccepted($id)
    {
        $order = Order::find($id);
        $transaction = $order->transaction;

        $order->order_status = 'PROCESS';
        $order->save();

        $transaction->status = 'PAID';
        $transaction->paid_at = now();
        $transaction->save();

        foreach($order->items as $item) {
           $this->setStock($item->sku, $item->quantity, true);
        }

        return response([ 'success' => true ], 200);
    }
    public function filterOrder(Request $request)
    {
        $request->validate([
            'filter' => ['required', 'string']
        ]);

        $skip = $request->skip?? 0;

        return response()->json([
            'success' => true,
            'results' => Order::with('items.product')
                ->where('order_status', $request->filter)
                ->orderByDesc('updated_at')
                ->skip($skip)
                ->take(4)
                ->get()
        ], 200);
    }
    public function searchOrder(Request $request)
    {
        $request->validate([
            'key' => ['required', 'string']
        ]);
        
        $q = filter_var($request->key, FILTER_SANITIZE_SPECIAL_CHARS);

        return response()->json([
            'success' => true,
            'results' => Order::with('items.product')
                ->where('customer_whatsapp', $q)
                ->orWhere('order_ref', $q)
                ->orderByDesc('updated_at')
                ->get()
        ], 200);
    }
    public function searchAdminOrder(Request $request)
    {
        $request->validate([
            'key' => ['required', 'string']
        ]);
        $q = filter_var($request->key, FILTER_SANITIZE_SPECIAL_CHARS);

        return response()->json([
            'success' => true,
            'results' => Order::with('transaction')
                ->where('customer_whatsapp', 'like', '%'.$q .'%')
                ->orWhere('order_ref', 'like', '%'.$q .'%')
                ->orderByDesc('updated_at')
                ->get()
        ], 200);
    }
    public function inputResi(Request $request)
    {
        $request->validate([
            'order_id' => ['required'],
            'resi' => ['required'],
        ]);
        $order = Order::findOrFail($request->order_id);

        $order->shipping_courier_code = $request->resi;
        $order->shipping_delivered = now();
        $order->order_status = 'SHIPPING';

        $order->save();

        return response([ 'success' => true ], 200);
    }

    protected function getOrders($request) 
    {
        $search = $request->query('search') ?? null;
        $filter = $request->query('filter') ?? null;
        $skip = $request->query('skip')?? 0;
        $take = $request->query('take')?? 4;

        if($search) {
            return Order::with('transaction', 'seller')->where('customer_whatsapp', $search)
                ->orWhere('order_ref', $search)
                ->orderByDesc('updated_at')
                ->get();
        }
        if($filter && $filter != 'ALL') {
            return Order::with('transaction', 'seller')
                ->where('order_status', $filter)
                ->orderByDesc('updated_at')
                ->skip($skip)
                ->take($take)
                ->get();
        }

        return Order::with('transaction', 'seller')->latest()->skip($skip)->take($take)->get();

    }

    public function getRandomOrder()
    {
        $items = DB::table('order_items')
        ->select('order_items.id', 'order_items.name', 'order_items.created_at', 'orders.customer_name', 'assets.filename')
        ->join('orders', 'order_items.order_id', 'orders.id')
        ->join('products', 'order_items.product_id', 'products.id')
        ->join('assets', function($join) {
            $join->on('products.id', '=', 'assets.assetable_id')
                    ->where('assets.assetable_type', '=', 'App\Models\Product');
        })
        ->inRandomOrder()
        ->get()->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'customer_name' => $item->customer_name,
                'created' => $item->created_at >= Carbon::now()->subDays(2)? Carbon::parse($item->created_at)->diffForHumans() : 'Beberapa waktu lalu',
                'image' => url('/upload/images/' . $item->filename)
            ];
        });

        return response()->json(['results' => $items], 200);
    }
    public function updateStatusOrder(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'status' => 'required',
        ]);

        $order = Order::find($request->order_id);
        $order->order_status = $request->status;

        $order->save();

        if($order->shipping_courier_name == 'COD' && $request->status == 'COMPLETE') {

            $order->transaction()->update([
                'status' => 'PAID'
            ]);

        }

        Order::where('id', $request->order_id)->update([
            'order_status' => $request->status
        ]);

        return response([ 'success' => true ], 200);
    }
    public function cancelOrder($id)
    {
        $order = Order::findOrFail($id);

            foreach($order->items as $item) {

                $this->setStock($item->sku, $item->quantity);

            }

        $order->update(['order_status' => 'CANCELED']);

        return response()->json(['success' => true]);
    }
    protected function setStock($sku, $qty, $decrement = false)
    {
        $productData = Product::where('sku', $sku)->first();

        if(!$productData) {

            $productData = ProductVarian::where('sku', $sku)->first();
        }

        if($productData) {

            if($decrement) {

                $productData->stock -= $qty;

            } else {

                $productData->stock += $qty;
            }
            
            $productData->save();

        }
    }
    
}
