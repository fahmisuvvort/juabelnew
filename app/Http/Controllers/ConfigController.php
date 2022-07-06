<?php

namespace App\Http\Controllers;

use App\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ConfigController extends Controller
{

    public function show()
    {
        return response([
            'success' => true,
            'results' => Config::first()
        ], 200);
    }
    public function adminConfig()
    {
        $hiddenFields = [
            'rajaongkir_apikey',
            'tripay_api_key',
            'tripay_private_key',
            'tripay_merchant_code',
            'telegram_bot_token',
            'telegram_user_id',
        ];

        return response([
            'success' => true,
            'results' => Config::first()->makeVisible($hiddenFields)
        ], 200);
    }

    public function update(Request $request)
    {
        $config = Config::first();

        $config->update($request->all());

        Cache::forget('shop_config');
        Cache::forget('admin_config');

        return response([
            'success' => true,
            'results' => null
        ], 200);
    }

}
