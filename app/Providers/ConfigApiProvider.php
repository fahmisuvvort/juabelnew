<?php

namespace App\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class ConfigApiProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        try {
            //code...
            $setting = DB::table('configs')->first();

            $apiPro = env('RAJAONGKIR_API_PRO', null);
            if ($setting) //checking if table is not empty
            {
                $rajaongkir = array(
                    'api_key' => $apiPro?? $setting->rajaongkir_apikey,
                    'account_type' => $setting->rajaongkir_type,
                    'api_url' => env('RAJAONGKIR_API_URL', 'https://api.rajaongkir.com/starter/'),
                    'api_url_basic' => env('RAJAONGKIR_API_BASIC_URL', 'https://api.rajaongkir.com/basic/'),
                    'api_url_pro' => env('RAJAONGKIR_API_PRO_URL', 'https://pro.rajaongkir.com/api/'),
                    'courier_starter' => explode(',', env('RAJAONGKIR_STARTER')),
                    'courier_basic' => explode(',', env('RAJAONGKIR_BASIC')),
                    'courier_pro' => explode(',', env('RAJAONGKIR_PRO')),
                );
    
                if($setting->telegram_bot_token && $setting->telegram_user_id) {
                    Config::set('services.telegram-bot-api', ['token' =>$setting->telegram_bot_token]);
                    Config::set('telegram', ['user_id' =>$setting->telegram_user_id]);
                }
                
                Config::set('rajaongkir', $rajaongkir);
                
                if($setting->tripay_api_key && $setting->tripay_private_key && $setting->tripay_merchant_code ) {
                    
                    Config::set('tripay', [
                        'api_key' => $setting->tripay_api_key,
                        'private_key' => $setting->tripay_private_key,
                        'mode' => $setting->tripay_mode,
                        'merchant_code' => $setting->tripay_merchant_code,
                        'api_sanbox_url' => env('TRIPAY_API_SANBOX_URL', 'https://tripay.co.id/api-sandbox/'),
                        'api_url' => env('TRIPAY_API_URL', 'https://tripay.co.id/api/')
                    ]);
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
