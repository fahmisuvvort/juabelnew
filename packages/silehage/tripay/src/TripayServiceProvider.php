<?php

namespace Silehage\Tripay;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class TripayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('Tripay', function() {
            return new Tripay;
        });
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
