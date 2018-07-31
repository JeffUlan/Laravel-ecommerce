<?php

namespace Webkul\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Blade;
use Webkul\Admin\Providers\EventServiceProvider;
use Webkul\Admin\Providers\ComposerServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'admin');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/admin/assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'admin');

        $this->composeView();

        $this->app->register(EventServiceProvider::class);
        $this->app->register(ComposerServiceProvider::class);
    }

    /**
     * Bind the the data to the views
     *
     * @return void
     */
    protected function composeView()
    {
        view()->composer(['admin::layouts.nav-left', 'admin::layouts.nav-aside', 'admin::layouts.tabs'], function ($view) {
            $menu = current(Event::fire('admin.menu.create'));

            $keys = explode('.', $menu->currentKey);
            $subMenus = $tabs = [];
            if (count($keys) > 1) {
                $subMenus = [
                        'items' => $menu->sortItems(array_get($menu->items, current($keys) . '.children')),
                        'current' => $menu->current,
                        'currentKey' => $menu->currentKey
                    ];

                if (count($keys) > 2) {
                    $tabs = [
                            'items' => $menu->sortItems(array_get($menu->items, implode('.children.', array_slice($keys, 0, 2)) . '.children')),
                            'current' => $menu->current,
                            'currentKey' => $menu->currentKey
                        ];
                }
            }

            $view->with('menu', $menu)->with('subMenus', $subMenus)->with('tabs', $tabs);
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->mergeConfigFrom(
        //     __DIR__ . '/../Config/auth.php',
        //     'auth'
        // );
    }

    /**
     * Merge the given configuration with the existing configuration.
     *
     * @param  string  $path
     * @param  string  $key
     * @return void
     */
    protected function mergeConfigFrom($path, $key)
    {
        $config = $this->app['config']->get($key, []);

        $this->app['config']->set($key, array_merge($config, require $path));
    }
}
