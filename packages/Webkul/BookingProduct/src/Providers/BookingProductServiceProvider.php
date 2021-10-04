<?php

namespace Webkul\BookingProduct\Providers;

use Illuminate\Support\ServiceProvider;

class BookingProductServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Http/front-routes.php');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'bookingproduct');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'bookingproduct');

        $this->publishes([
            __DIR__.'/../../publishable/assets' => public_path('themes/default/assets'),
        ], 'public');

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__).'/Config/product_types.php', 'product_types');
    }

}