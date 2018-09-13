<?php

namespace Webkul\Cart\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Webkul\Customer\Http\Middleware\RedirectIfNotCustomer;

class CartServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {

        // $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('datagrid', 'Webkul\Ui\DataGrid\DataGrid');
    }
}
