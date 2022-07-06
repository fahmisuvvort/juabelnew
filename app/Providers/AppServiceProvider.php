<?php

namespace App\Providers;

use App\Models\Store;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        $shop = null;

        try {
 
            $shop = Cache::rememberForever('shop', function () {
                return Store::first();
            });

        } catch (\Throwable $th) {
            //throw $th;
        }


        View::macro('vue', function($page = []) use ($shop) {
            return view('app', [
                'jsapp' => [
                    'page' => $page,
                    'shop' => $shop
                ]
            ]);
        });
    }
}
