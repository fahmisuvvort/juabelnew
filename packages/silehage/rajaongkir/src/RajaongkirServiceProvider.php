<?php

namespace Silehage\Rajaongkir;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class RajaongkirServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('Rajaongkir', function() {
            return new Rajaongkir;
        });

        $this->app->make('Silehage\Rajaongkir\RajaongkirController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';
    }
}
