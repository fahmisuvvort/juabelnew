<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\Slider;
use App\Models\Product;
use App\Models\BankAccount;
use App\Observers\PostObserver;
use App\Observers\SliderObserver;
use App\Observers\ProductObserver;
use Illuminate\Support\Facades\Event;
use App\Observers\BankAccountObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Slider::observe(SliderObserver::class);
        Post::observe(PostObserver::class);
        BankAccount::observe(BankAccountObserver::class);
        Product::observe(ProductObserver::class);
    }
}
