<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index(Request $request)
    {
        return response([
            'success' => true,
            'results' => $request->user()
        ], 200);
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:80', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ],[
            'name.required' => 'Nama wajib diisi.',
            'phone.required' => 'Nomor ponsel wajib diisi.',
            'phone.unique' => 'Nomor ponsel sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Password konfirmasi tidak sama.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response([
            'success' => true,
            'token' => $token,
            'results' => $user
        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'device_name' => 'required',
        ], [
            'email.required' => 'Email atau No ponsel wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);
    
        $user = User::where('email', $request->email)->orWhere('phone', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Data kredensial salah.'],
            ]);
        }
        $user->tokens()->delete();
    
        $token = $user->createToken($request->device_name)->plainTextToken;
        return response([
            'success' => true,
            'token' => $token,
            'results' => $user
        ], 200);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' .$user->id,
            'email' => 'required|email|unique:users,email,' .$user->id,
            'password' => 'nullable|confirmed'
        ],[
            'name.required' => 'Nama wajib diisi.',
            'phone.required' => 'Nomor ponsel wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'phone.unique' => 'Nomor ponsel sudah terdaftar.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.confirmed' => 'Password konfirmasi salah.',
        ]);


        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response([
            'success' => true,
            'results' => $user
        ], 200);


    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response('logout', 200);
    }

    public function userList(Request $request)
    {

        return response([
            'success' => true,
            'results' => User::skip($request->skip)->take($request->take)->latest()->get()
        ]);
    }
    public function findUser($key)
    {
        $key = htmlspecialchars($key);

        return response([
            'success' => true,
            'results' => User::where('name', 'like', '%' . $key . '%')
                ->orWhere('email', 'like', '%' . $key . '%')
                ->orWhere('phone', 'like', '%' . $key . '%')
                ->get()
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        $user->delete();

        return response([ 'success' => true ]);

    }
    
}
