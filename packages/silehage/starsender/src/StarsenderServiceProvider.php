<?php

namespace Silehage\Starsender;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class StarsenderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('starsender', function() {
            return new Starsender;
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
