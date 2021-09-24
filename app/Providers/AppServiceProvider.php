<?php

namespace App\Providers;

use App\Http\Components\Builders\DefaultMenuBuilder;
use App\Http\Components\DataGetter;
use Illuminate\Support\Facades\View;
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
        $this->app->singleton(DefaultMenuBuilder::class, function ($app) {
            return new DefaultMenuBuilder();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::share('dataGetter', $this->app->make(DataGetter::class));
    }
}
