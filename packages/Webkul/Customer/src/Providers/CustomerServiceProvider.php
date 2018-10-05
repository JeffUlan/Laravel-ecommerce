<?php

namespace Webkul\Customer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Webkul\Customer\Http\Middleware\RedirectIfNotCustomer;
use Webkul\Customer\Providers\EventServiceProvider;

class CustomerServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'customer');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'customer');

        $this->composeView();

        $this->app->register(EventServiceProvider::class);
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

    protected function composeView()
    {
        view()->composer(['shop::customers.account.partials.sidemenu'], function ($view) {
            $menu = current(Event::fire('customer.menu.create'));

            $view->with('menu', $menu);
        });
    }
}
