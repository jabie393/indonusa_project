<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Order;
use App\Models\Goods;
use App\Observers\OrderObserver;
use App\Observers\GoodsObserver;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Listeners\BroadcastUserLogin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (str_starts_with(config('app.url'), 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        if (app()->environment('production', 'local', 'staging')) {
            Order::observe(OrderObserver::class);
            \App\Models\Goods::observe(\App\Observers\GoodsObserver::class);
        }
    }
}
