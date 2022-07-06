<?php

namespace App\Http\Controllers;


use App\Models\Config;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Store as Shop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;

class StoreController extends Controller
{

    public function index()
    {
        $shop = Cache::rememberForever('shop', function () {
            return Shop::first();
        });
        $config = Cache::rememberForever('shop_config', function () {
            return Config::first();
        });
        return response([
            'success' => true,
            'results' => [
                'shop' => $shop,
                'config' => $config
            ]
        ], 200);
    }

    public function update(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|numeric'
        ]);
        
        DB::beginTransaction();

        try {
            $shop = Shop::first();

            $path = public_path('/upload/images');

            if(! File::exists($path)) {
                File::makeDirectory($path, 0755, true, true);
            }

            $iconPath = public_path('/icon');

            if (!File::isDirectory($iconPath)) {
    
                File::makeDirectory($iconPath, 0777, true, true);
            }

            $shop->name = $request->name;
            $shop->phone = $request->phone;
            if(!$shop->slug) {
                $shop->slug = $request->name? Str::slug($request->name) : null;
            }
            $shop->address = $request->address;
            $shop->description = $request->description;

            if($request->boolean('is_remove_logo') || $request->file('logo')) {
                if($shop->logo_path){
                    File::delete($shop->logo_path);
                    File::delete(
                        'icon/logo.png',
                        'icon/icon-384x384.png',
                        'icon/icon-256x256.png',
                        'icon/icon-192x192.png',
                        'icon/icon-180x180.png',
                        'icon/icon-167x167.png',
                        'icon/icon-152x152.png',
                        'icon/icon-144x144.png',
                        'icon/icon-128x128.png',
                        'icon/icon-120x120.png',
                        'icon/icon-96x96.png',
                        'icon/favicon.png',
                        'icon/icon-512x512.png',
                    );
                    $shop->logo_path = NULL;
                }
            }

            if($file = $request->file('logo')) {
                if($shop->logo_path) {
                    File::delete($shop->logo_path);
                }
           
                $rawFile = Image::make($file);
        
                $rawFile->resize(512,512)->encode('png')->save('icon/icon-512x512.png');
                $rawFile->resize(384,384)->encode('png')->save('icon/logo.png');
                $rawFile->resize(384,384)->encode('png')->save('icon/icon-384x384.png');
                $rawFile->resize(256,256)->encode('png')->save('icon/icon-256x256.png');
                $rawFile->resize(192,192)->encode('png')->save('icon/icon-192x192.png');
                $rawFile->resize(180,180)->encode('png')->save('icon/icon-180x180.png');
                $rawFile->resize(167,167)->encode('png')->save('icon/icon-167x167.png');
                $rawFile->resize(152,152)->encode('png')->save('icon/icon-152x152.png');
                $rawFile->resize(144,144)->encode('png')->save('icon/icon-144x144.png');
                $rawFile->resize(128,128)->encode('png')->save('icon/icon-128x128.png');
                $rawFile->resize(120,120)->encode('png')->save('icon/icon-120x120.png');
                $rawFile->resize(96,96)->encode('png')->save('icon/icon-96x96.png');
                $rawFile->resize(64,64)->encode('png')->save('icon/favicon.png'); 

                $filename = Str::random(20) . '.' . $file->extension();
                
                $file->move($path, $filename);

                $shop->logo_path = 'upload/images/' .$filename;

                
            }

            $shop->save();

            DB::commit();
            Cache::forget('shop');

            return response([
                'success' => true,
                'results' => $shop
            ], 200);
            
        } catch (\Throwable $th) {

            DB::rollBack();

            return response([
                'success' => false,
                'results' => null
            ], 500);
        }

        
    }
}
