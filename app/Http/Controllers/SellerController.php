<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{
    protected $limit = 6;
    public function index()
    {
        return response([
            'success' => true,
            'results' => User::where('role', 'seller')->get()
        ]);
    }
    public function show($id)
    {
        return response([
            'success' => true,
            'results' => User::find($id)
        ]);
    }
    public function updateSeller(Request $request)
    {
        $user = $request->user();

        $shop = $user->shop;

        $shop->name = $request->shop_name;
        $shop->warehouse = $request->warehouse;
        $shop->save();

        return response()->json([
            'success' => true,
            'results' => $shop->fresh()
        ]);

    }
    public function getSeller(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'results' => $user->shop
        ]);

    }
    public function findSeller($key)
    {
        $key = htmlspecialchars($key);

        return response([
            'success' => true,
            'results' => User::where('name', 'like', '%' . $key . '%')
                ->orWhere('email', 'like', '%' . $key . '%')
                ->orWhere('phone', 'like', '%' . $key . '%')
                ->where('role', 'seller')
                ->get()
        ]);
    }
    public function destroy($id)
    {
        $user = User::find($id);

        if($user->shop) {

            $user->shop()->delete();
        }

        $user->delete();

        return response([
            'success' => true
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'shop_name' => ['required', 'string', 'max:60','unique:sellers,name'],
            'phone' => ['required', 'string', 'max:16', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:80', 'unique:users'],
            'password' => ['required'],
        ],[
            'shop_name.required' => 'Nama toko wajib diisi.',
            'shop_name.unique' => 'Nama toko sudah terdaftar.',
            'name.required' => 'Nama wajib diisi.',
            'phone.required' => 'Nomor ponsel wajib diisi.',
            'phone.unique' => 'Nomor ponsel sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'seller'
            ]);

            $user->shop()->create([
                'name' => $request->shop_name, 
                'slug' => Str::slug($request->shop_name),
            ]);

            DB::commit();

            return response([
                'success' => true,
                'results' => $user
            ], 201);

        
        } catch (\Throwable $th) {

            DB::rollback();
            return response([
                'success' => true,
                'message' => $th->getMessage()
            ], 201);
        }


    }
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'phone' => ['required', 'string', 'max:16', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:80', 'unique:users'],
        ],[
            'name.required' => 'Nama wajib diisi.',
            'phone.required' => 'Nomor ponsel wajib diisi.',
            'phone.unique' => 'Nomor ponsel sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->role = $request->role;

            if($request->password) {
                $user->password = Hash::make($request->password);
            }

            $user->save();


        return response([
            'success' => true,
            'results' => $user
        ], 201);
    }
    public function searchProducts(Request $request, $key)
    {
        $seller = $request->user()->id;
        $result = [
            'status' => 200,
            'count' => 0,
            'limit' => $this->limit
        ];
        try {
            $result['count'] = Product::where('seller_id', $seller)->count();
            $result['results'] = Product::with(['assets', 'category'])
                    ->latest()
                    ->where('seller_id', $seller)
                    ->where('title', 'like', '%'. $key . '%')
                    ->get();


        } catch (Exception $e) {

            $result = [
                'status' => 500,
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }
    public function products(Request $request, $seller = null)
    {
        if(!$seller) {

            $user= $request->user();

            if($user->shop) {
                $seller = $user->shop->id;
            }

        }

        $result = [
            'status' => 200,
            'count' => 0,
            'limit' => $this->limit
        ];
        try {
            $result['count'] = Product::where('seller_id', $seller)->count();
            $result['results'] = Product::with(['assets', 'category'])
                    ->latest()
                    ->where('seller_id', $seller)
                    ->take($this->limit)
                    ->skip($request->skip ?? 0)
                    ->get();


        } catch (Exception $e) {

            $result = [
                'status' => 500,
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }
    public function orders(Request $request, $seller = null)
    {
        if(!$seller) {
            $user = $request->user();
            if($shop = $user->shop) {
                $seller = $shop->id;
            }
        }

        $result = [
            'status' => 200,
            'limit' => $this->limit,
            'count' => 0,
            'results' => []
        ];
        try {

            $instance = Order::with('transaction');

            if($request->filter && $request->filter != 'ALL') {
                
                $instance->where('order_status', $request->filter);
            }

            $result['results'] = $instance
                    ->with('seller')
                    ->orderByDesc('updated_at')
                    ->where('seller_id', $seller)
                    ->take($this->limit)
                    ->skip($request->skip ?? 0)
                    ->take($this->limit)
                    ->get();

            $result['count'] = Order::where('seller_id', $seller)->count();

        } catch (Exception $e) {

            $result = [
                'status' => 500,
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
        return response()->json($result, $result['status']);
    }

}
