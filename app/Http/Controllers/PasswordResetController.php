<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Notifications\ResetPasswordNotification;

class PasswordResetController extends Controller
{
    public function requestPasswordToken(Request $request)
    {
        $request->validate([
            'email' => ['required']
        ]);
        $user = User::where('email', $request->email)->first();

        if(!$user) {
            return response([
                'OK' => false,
                'message' => 'Email tidak terdaftar.'
            ], 200);
        }
        $isReady = PasswordReset::where('email', $request->email)->first();

        if($isReady) {

            if(Carbon::parse($isReady->created_at)->addHour() > now()) {
                return response([
                    'OK' => false,
                    'message' => 'Anda baru saja membuat permintaan request password, Tunggu 60 menit untuk membuat permintaan kembali.'
                ], 200);
            }
            PasswordReset::where('email', $request->email)->delete();
        }

        DB::beginTransaction();
        
        try {
            $token = Str::upper(Str::random(rand(8, 12)));
            PasswordReset::create([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]);
            
            $user->notify(new ResetPasswordNotification($token));

            DB::commit();

            return response([
                'OK' => true,
                'token' => $token,
                'email' => $request->email
            ], 200);

        } catch (\Throwable $th) {
            //throw $th;

            DB::rollBack();
            
            return response([
                'OK' => false,
                'message' => $th->getMessage(),
            ], 200);
        }

        return response([
            'OK' => true,
            'token' => $token,
            'email' => $request->email
        ], 200);
        
    }
    public function validateToken($token)
    {

        $tkn =  trim(htmlspecialchars(strip_tags($token)));
        if(!$tkn) {
            return response([
                'OK' => false,
                'message' => 'Token Salah, Buka email anda dan masukkan token yang benar'
            ], 200);
        }
       
        if( $data = PasswordReset::where('token', $tkn)->first()) {
            return response([
                'OK' => true,
                'data' => $data
            ], 200);
        }
        return response([
            'OK' => false,
            'message' => 'Token Salah, Buka email anda dan masukkan token yang benar'
        ], 200);

    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $tkn = filter_var($request->token, FILTER_SANITIZE_SPECIAL_CHARS);
        
        $data = PasswordReset::where('token', $tkn)->first();
        $user = User::where('email', $data->email)->first();

        if(!$data || !$user) {
            return response([
                'message' => 'Not Authenticated'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        PasswordReset::where('email', $data->email)->delete();

        return response([
            'OK' => true,
            'message' => 'Silahkan Login dengan password baru anda.'
        ], 200);
    }
}
