<?php

namespace Webkul\Velocity\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Webkul\Velocity\Facades\Velocity as VelocityFacade;

/**
 * Velocity ServiceProvider
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @author Shubham Mehrotra <shubhammehrotra.symfony@webkul.com> @shubhwebkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class VelocityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/helpers.php';
        include __DIR__ . '/../Http/admin-routes.php';
        include __DIR__ . '/../Http/front-routes.php';

        $this->app->register(EventServiceProvider::class);

        $this->loadGloableVariables();
        $this->loadPublishableAssets();
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'velocity');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'velocity');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerFacades();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        // $this->mergeConfigFrom(
        //     dirname(__DIR__) . '/Config/system.php', 'core'
        // );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('velocity', VelocityFacade::class);
    }

    // this function will provide global variables shared by view (blade files)
    private function loadPublishableAssets()
    {
        $this->publishes([
            __DIR__ . '/../../publishable/assets/' => public_path('themes/velocity/assets'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/../Resources/views/shop' => resource_path('themes/velocity/views'),
        ]);

        return true;
    }

    // this function will provide global variables shared by view (blade files)
    private function loadGloableVariables()
    {
        $velocityHelper = app('Webkul\Velocity\Helpers\Helper');
        $velocityMetaData = $velocityHelper->getVelocityMetaData();

        view()->share('showRecentlyViewed', true);
        view()->share('velocityMetaData', $velocityMetaData);

        return true;
    }
}
