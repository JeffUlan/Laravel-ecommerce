<?php

Route::group(['middleware' => ['web']], function () {
    Route::prefix('admin')->group(function () {
        Route::get('/grid', 'Webkul\Product\Http\Controllers\ProductController@test');

        // Login Routes
        Route::get('/login', 'Webkul\User\Http\Controllers\SessionController@create')->defaults('_config', [
            'view' => 'admin::users.sessions.create'
        ])->name('admin.session.create');

        Route::post('/login', 'Webkul\User\Http\Controllers\SessionController@store')->defaults('_config', [
            'redirect' => 'admin.dashboard.index'
        ])->name('admin.session.store');


        // Forget Password Routes
        Route::get('/forget-password', 'Webkul\User\Http\Controllers\ForgetPasswordController@create')->defaults('_config', [
            'view' => 'admin::users.forget-password.create'
        ])->name('admin.forget-password.create');

        Route::post('/forget-password', 'Webkul\User\Http\Controllers\ForgetPasswordController@store')->name('admin.forget-password.store');


        // Reset Password Routes
        Route::get('/reset-password/{token}', 'Webkul\User\Http\Controllers\ResetPasswordController@create')->defaults('_config', [
            'view' => 'admin::users.reset-password.create'
        ])->name('admin.reset-password.create');

        Route::post('/reset-password', 'Webkul\User\Http\Controllers\ResetPasswordController@store')->defaults('_config', [
            'redirect' => 'admin.dashboard.index'
        ])->name('admin.reset-password.store');


        // Admin Routes
        Route::group(['middleware' => ['admin']], function () {
            Route::get('/logout', 'Webkul\User\Http\Controllers\SessionController@destroy')->defaults('_config', [
                'redirect' => 'admin.session.create'
            ])->name('admin.session.destroy');

            // Dashboard Route
            Route::get('dashboard', 'Webkul\Admin\Http\Controllers\DashboardController@index')->name('admin.dashboard.index');

            //Customers Management Routes
            Route::get('customers', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@index')->defaults('_config', [
                'view' => 'admin::customers.index'
            ])->name('admin.customer.index');

            Route::get('customers/orders', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@index')->defaults('_config',[
                'view' => 'admin::customers.orders.index'
            ])->name('admin.customer.orders.index');

            Route::get('customers/create', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@create')->defaults('_config',[
                'view' => 'admin::customers.create'
            ])->name('admin.customer.create');

            Route::post('customers/create', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@store')->defaults('_config',[
                'redirect' => 'admin.customer.index'
            ])->name('admin.customer.store');

            Route::get('customers/edit/{id}', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@edit')->defaults('_config',[
                'view' => 'admin::customers.edit'
            ])->name('admin.customer.edit');

            Route::put('customers/edit/{id}', 'Webkul\Admin\Http\Controllers\Customer\CustomerController@update')->defaults('_config', [
                'redirect' => 'admin.customer.index'
            ])->name('admin.customer.update');

            Route::get('customers/delete/{id}', 'Webkul\Admin\Http\Controllers\CustomerController@destroy')->name('admin.customer.delete');


            Route::get('reviews', 'Webkul\Product\Http\Controllers\ReviewController@index')->defaults('_config',[
                'view' => 'admin::customers.review.index'
            ])->name('admin.customer.review.index');

            Route::get('reviews/edit/{id}', 'Webkul\Product\Http\Controllers\ReviewController@edit')->defaults('_config',[
                'view' => 'admin::customers.review.edit'
            ])->name('admin.customer.review.edit');

            Route::put('reviews/edit/{id}', 'Webkul\Product\Http\Controllers\ReviewController@update')->defaults('_config', [
                'redirect' => 'admin.customer.review.index'
            ])->name('admin.customer.review.update');


            // Sales Routes
            Route::prefix('sales')->group(function () {
                // Sales Order Routes
                Route::get('/orders', 'Webkul\Admin\Http\Controllers\Sales\OrderController@index')->defaults('_config', [
                    'view' => 'admin::sales.orders.index'
                ])->name('admin.sales.orders.index');

                Route::get('/orders/view/{id}', 'Webkul\Admin\Http\Controllers\Sales\OrderController@view')->defaults('_config', [
                    'view' => 'admin::sales.orders.view'
                ])->name('admin.sales.orders.view');

                Route::get('/orders/cancel/{id}', 'Webkul\Admin\Http\Controllers\Sales\OrderController@cancel')->defaults('_config', [
                    'view' => 'admin::sales.orders.cancel'
                ])->name('admin.sales.orders.cancel');


                // Sales Invoices Routes
                Route::get('/invoices', 'Webkul\Admin\Http\Controllers\Sales\InvoiceController@index')->defaults('_config', [
                    'view' => 'admin::sales.invoices.index'
                ])->name('admin.sales.invoices.index');

                Route::get('/invoices/create/{order_id}', 'Webkul\Admin\Http\Controllers\Sales\InvoiceController@create')->defaults('_config', [
                    'view' => 'admin::sales.invoices.create'
                ])->name('admin.sales.invoices.create');

                Route::post('/invoices/create/{order_id}', 'Webkul\Admin\Http\Controllers\Sales\InvoiceController@store')->defaults('_config', [
                    'redirect' => 'admin.sales.orders.view'
                ])->name('admin.sales.invoices.store');

                Route::get('/invoices/view/{id}', 'Webkul\Admin\Http\Controllers\Sales\InvoiceController@view')->defaults('_config', [
                    'view' => 'admin::sales.invoices.view'
                ])->name('admin.sales.invoices.view');


                // Sales Shipments Routes
                Route::get('/shipments', 'Webkul\Admin\Http\Controllers\Sales\ShipmentController@index')->defaults('_config', [
                    'view' => 'admin::sales.shipments.index'
                ])->name('admin.sales.shipments.index');

                Route::get('/shipments/create/{order_id}', 'Webkul\Admin\Http\Controllers\Sales\ShipmentController@create')->defaults('_config', [
                    'view' => 'admin::sales.shipments.create'
                ])->name('admin.sales.shipments.create');

                Route::post('/shipments/create/{order_id}', 'Webkul\Admin\Http\Controllers\Sales\ShipmentController@store')->defaults('_config', [
                    'redirect' => 'admin.sales.orders.view'
                ])->name('admin.sales.shipments.store');

                Route::get('/shipments/view/{id}', 'Webkul\Admin\Http\Controllers\Sales\ShipmentController@view')->defaults('_config', [
                    'view' => 'admin::sales.shipments.view'
                ])->name('admin.sales.shipments.view');
            });

            // Catalog Routes
            Route::prefix('catalog')->group(function () {

                Route::get('/sync', 'Webkul\Product\Http\Controllers\ProductController@sync');

                // Catalog Product Routes
                Route::get('/products', 'Webkul\Product\Http\Controllers\ProductController@index')->defaults('_config', [
                    'view' => 'admin::catalog.products.index'
                ])->name('admin.catalog.products.index');

                Route::get('/products/create', 'Webkul\Product\Http\Controllers\ProductController@create')->defaults('_config', [
                    'view' => 'admin::catalog.products.create'
                ])->name('admin.catalog.products.create');

                Route::post('/products/create', 'Webkul\Product\Http\Controllers\ProductController@store')->defaults('_config', [
                    'redirect' => 'admin.catalog.products.edit'
                ])->name('admin.catalog.products.store');

                Route::get('/products/edit/{id}', 'Webkul\Product\Http\Controllers\ProductController@edit')->defaults('_config', [
                    'view' => 'admin::catalog.products.edit'
                ])->name('admin.catalog.products.edit');

                Route::put('/products/edit/{id}', 'Webkul\Product\Http\Controllers\ProductController@update')->defaults('_config', [
                    'redirect' => 'admin.catalog.products.index'
                ])->name('admin.catalog.products.update');

                Route::get('/products/delete/{id}', 'Webkul\Product\Http\Controllers\ProductController@destroy')->name('admin.catalog.products.delete');


                // Catalog Category Routes
                Route::get('/categories', 'Webkul\Category\Http\Controllers\CategoryController@index')->defaults('_config', [
                    'view' => 'admin::catalog.categories.index'
                ])->name('admin.catalog.categories.index');

                Route::get('/categories/create', 'Webkul\Category\Http\Controllers\CategoryController@create')->defaults('_config', [
                    'view' => 'admin::catalog.categories.create'
                ])->name('admin.catalog.categories.create');

                Route::post('/categories/create', 'Webkul\Category\Http\Controllers\CategoryController@store')->defaults('_config', [
                    'redirect' => 'admin.catalog.categories.index'
                ])->name('admin.catalog.categories.store');

                Route::get('/categories/edit/{id}', 'Webkul\Category\Http\Controllers\CategoryController@edit')->defaults('_config', [
                    'view' => 'admin::catalog.categories.edit'
                ])->name('admin.catalog.categories.edit');

                Route::put('/categories/edit/{id}', 'Webkul\Category\Http\Controllers\CategoryController@update')->defaults('_config', [
                    'redirect' => 'admin.catalog.categories.index'
                ])->name('admin.catalog.categories.update');

                Route::get('/categories/delete/{id}', 'Webkul\Category\Http\Controllers\CategoryController@destroy')->name('admin.catalog.categories.delete');


                // Catalog Attribute Routes
                Route::get('/attributes', 'Webkul\Attribute\Http\Controllers\AttributeController@index')->defaults('_config', [
                    'view' => 'admin::catalog.attributes.index'
                ])->name('admin.catalog.attributes.index');

                Route::get('/attributes/create', 'Webkul\Attribute\Http\Controllers\AttributeController@create')->defaults('_config', [
                    'view' => 'admin::catalog.attributes.create'
                ])->name('admin.catalog.attributes.create');

                Route::post('/attributes/create', 'Webkul\Attribute\Http\Controllers\AttributeController@store')->defaults('_config', [
                    'redirect' => 'admin.catalog.attributes.index'
                ])->name('admin.catalog.attributes.store');

                Route::get('/attributes/edit/{id}', 'Webkul\Attribute\Http\Controllers\AttributeController@edit')->defaults('_config', [
                    'view' => 'admin::catalog.attributes.edit'
                ])->name('admin.catalog.attributes.edit');

                Route::put('/attributes/edit/{id}', 'Webkul\Attribute\Http\Controllers\AttributeController@update')->defaults('_config', [
                    'redirect' => 'admin.catalog.attributes.index'
                ])->name('admin.catalog.attributes.update');

                Route::get('/attributes/delete/{id}', 'Webkul\Attribute\Http\Controllers\AttributeController@destroy')->name('admin.catalog.attributes.delete');


                // Catalog Family Routes
                Route::get('/families', 'Webkul\Attribute\Http\Controllers\AttributeFamilyController@index')->defaults('_config', [
                    'view' => 'admin::catalog.families.index'
                ])->name('admin.catalog.families.index');

                Route::get('/families/create', 'Webkul\Attribute\Http\Controllers\AttributeFamilyController@create')->defaults('_config', [
                    'view' => 'admin::catalog.families.create'
                ])->name('admin.catalog.families.create');

                Route::post('/families/create', 'Webkul\Attribute\Http\Controllers\AttributeFamilyController@store')->defaults('_config', [
                    'redirect' => 'admin.catalog.families.index'
                ])->name('admin.catalog.families.store');

                Route::get('/families/edit/{id}', 'Webkul\Attribute\Http\Controllers\AttributeFamilyController@edit')->defaults('_config', [
                    'view' => 'admin::catalog.families.edit'
                ])->name('admin.catalog.families.edit');

                Route::put('/families/edit/{id}', 'Webkul\Attribute\Http\Controllers\AttributeFamilyController@update')->defaults('_config', [
                    'redirect' => 'admin.catalog.families.index'
                ])->name('admin.catalog.families.update');

                Route::get('/families/delete/{id}', 'Webkul\Attribute\Http\Controllers\AttributeFamilyController@destroy')->name('admin.catalog.families.delete');
            });


            // Datagrid Routes

            //for datagrid and its loading, filtering, sorting and queries
            Route::get('datagrid', 'Webkul\Admin\Http\Controllers\DataGridController@index')->name('admin.datagrid.index');

            Route::any('datagrid/massaction/delete', 'Webkul\Admin\Http\Controllers\DataGridController@massDelete')->name('admin.datagrid.delete');

            Route::any('datagrid/massaction/edit','Webkul\Admin\Http\Controllers\DataGridController@massUpdate')->name('admin.datagrid.edit');

            // User Routes
            Route::get('/users', 'Webkul\User\Http\Controllers\UserController@index')->defaults('_config', [
                'view' => 'admin::users.users.index'
            ])->name('admin.users.index');

            Route::get('/users/create', 'Webkul\User\Http\Controllers\UserController@create')->defaults('_config', [
                'view' => 'admin::users.users.create'
            ])->name('admin.users.create');

            Route::post('/users/create', 'Webkul\User\Http\Controllers\UserController@store')->defaults('_config', [
                'redirect' => 'admin.users.index'
            ])->name('admin.users.store');

            Route::get('/users/edit/{id}', 'Webkul\User\Http\Controllers\UserController@edit')->defaults('_config', [
                'view' => 'admin::users.users.edit'
            ])->name('admin.users.edit');

            Route::put('/users/edit/{id}', 'Webkul\User\Http\Controllers\UserController@update')->defaults('_config', [
                'redirect' => 'admin.users.index'
            ])->name('admin.users.update');


            // User Role Routes
            Route::get('/roles', 'Webkul\User\Http\Controllers\RoleController@index')->defaults('_config', [
                'view' => 'admin::users.roles.index'
            ])->name('admin.roles.index');

            Route::get('/roles/create', 'Webkul\User\Http\Controllers\RoleController@create')->defaults('_config', [
                'view' => 'admin::users.roles.create'
            ])->name('admin.roles.create');

            Route::post('/roles/create', 'Webkul\User\Http\Controllers\RoleController@store')->defaults('_config', [
                'redirect' => 'admin.roles.index'
            ])->name('admin.roles.store');

            Route::get('/roles/edit/{id}', 'Webkul\User\Http\Controllers\RoleController@edit')->defaults('_config', [
                'view' => 'admin::users.roles.edit'
            ])->name('admin.roles.edit');

            Route::put('/roles/edit/{id}', 'Webkul\User\Http\Controllers\RoleController@update')->defaults('_config', [
                'redirect' => 'admin.roles.index'
            ])->name('admin.roles.update');


            // Locale Routes
            Route::get('/locales', 'Webkul\Core\Http\Controllers\LocaleController@index')->defaults('_config', [
                'view' => 'admin::settings.locales.index'
            ])->name('admin.locales.index');

            Route::get('/locales/create', 'Webkul\Core\Http\Controllers\LocaleController@create')->defaults('_config', [
                'view' => 'admin::settings.locales.create'
            ])->name('admin.locales.create');

            Route::post('/locales/create', 'Webkul\Core\Http\Controllers\LocaleController@store')->defaults('_config', [
                'redirect' => 'admin.locales.index'
            ])->name('admin.locales.store');

            Route::get('/locales/edit/{id}', 'Webkul\Core\Http\Controllers\LocaleController@edit')->defaults('_config', [
                'view' => 'admin::settings.locales.edit'
            ])->name('admin.locales.edit');

            Route::put('/locales/edit/{id}', 'Webkul\Core\Http\Controllers\LocaleController@update')->defaults('_config', [
                'redirect' => 'admin.locales.index'
            ])->name('admin.locales.update');

            Route::get('/locales/delete/{id}', 'Webkul\Core\Http\Controllers\LocaleController@destroy')->name('admin.locales.delete');


            // Currency Routes
            Route::get('/currencies', 'Webkul\Core\Http\Controllers\CurrencyController@index')->defaults('_config', [
                'view' => 'admin::settings.currencies.index'
            ])->name('admin.currencies.index');

            Route::get('/currencies/create', 'Webkul\Core\Http\Controllers\CurrencyController@create')->defaults('_config', [
                'view' => 'admin::settings.currencies.create'
            ])->name('admin.currencies.create');

            Route::post('/currencies/create', 'Webkul\Core\Http\Controllers\CurrencyController@store')->defaults('_config', [
                'redirect' => 'admin.currencies.index'
            ])->name('admin.currencies.store');

            Route::get('/currencies/edit/{id}', 'Webkul\Core\Http\Controllers\CurrencyController@edit')->defaults('_config', [
                'view' => 'admin::settings.currencies.edit'
            ])->name('admin.currencies.edit');

            Route::put('/currencies/edit/{id}', 'Webkul\Core\Http\Controllers\CurrencyController@update')->defaults('_config', [
                'redirect' => 'admin.currencies.index'
            ])->name('admin.currencies.update');

            Route::get('/currencies/delete/{id}', 'Webkul\Core\Http\Controllers\CurrencyController@destroy')->name('admin.currencies.delete');


            // Exchange Rates Routes
            Route::get('/exchange_rates', 'Webkul\Core\Http\Controllers\ExchangeRateController@index')->defaults('_config', [
                'view' => 'admin::settings.exchange_rates.index'
            ])->name('admin.exchange_rates.index');

            Route::get('/exchange_rates/create', 'Webkul\Core\Http\Controllers\ExchangeRateController@create')->defaults('_config', [
                'view' => 'admin::settings.exchange_rates.create'
            ])->name('admin.exchange_rates.create');

            Route::post('/exchange_rates/create', 'Webkul\Core\Http\Controllers\ExchangeRateController@store')->defaults('_config', [
                'redirect' => 'admin.exchange_rates.index'
            ])->name('admin.exchange_rates.store');

            Route::get('/exchange_rates/edit/{id}', 'Webkul\Core\Http\Controllers\ExchangeRateController@edit')->defaults('_config', [
                'view' => 'admin::settings.exchange_rates.edit'
            ])->name('admin.exchange_rates.edit');

            Route::put('/exchange_rates/edit/{id}', 'Webkul\Core\Http\Controllers\ExchangeRateController@update')->defaults('_config', [
                'redirect' => 'admin.exchange_rates.index'
            ])->name('admin.exchange_rates.update');

            Route::get('/exchange_rates/delete/{id}', 'Webkul\Core\Http\Controllers\ExchangeRateController@destroy')->name('admin.exchange_rates.delete');


            // Inventory Source Routes
            Route::get('/inventory_sources', 'Webkul\Inventory\Http\Controllers\InventorySourceController@index')->defaults('_config', [
                'view' => 'admin::settings.inventory_sources.index'
            ])->name('admin.inventory_sources.index');

            Route::get('/inventory_sources/create', 'Webkul\Inventory\Http\Controllers\InventorySourceController@create')->defaults('_config', [
                'view' => 'admin::settings.inventory_sources.create'
            ])->name('admin.inventory_sources.create');

            Route::post('/inventory_sources/create', 'Webkul\Inventory\Http\Controllers\InventorySourceController@store')->defaults('_config', [
                'redirect' => 'admin.inventory_sources.index'
            ])->name('admin.inventory_sources.store');

            Route::get('/inventory_sources/edit/{id}', 'Webkul\Inventory\Http\Controllers\InventorySourceController@edit')->defaults('_config', [
                'view' => 'admin::settings.inventory_sources.edit'
            ])->name('admin.inventory_sources.edit');

            Route::put('/inventory_sources/edit/{id}', 'Webkul\Inventory\Http\Controllers\InventorySourceController@update')->defaults('_config', [
                'redirect' => 'admin.inventory_sources.index'
            ])->name('admin.inventory_sources.update');

            Route::get('/inventory_sources/delete/{id}', 'Webkul\Inventory\Http\Controllers\InventorySourceController@destroy')->name('admin.inventory_sources.delete');

            // Channel Routes
            Route::get('/channels', 'Webkul\Core\Http\Controllers\ChannelController@index')->defaults('_config', [
                'view' => 'admin::settings.channels.index'
            ])->name('admin.channels.index');

            Route::get('/channels/create', 'Webkul\Core\Http\Controllers\ChannelController@create')->defaults('_config', [
                'view' => 'admin::settings.channels.create'
            ])->name('admin.channels.create');

            Route::post('/channels/create', 'Webkul\Core\Http\Controllers\ChannelController@store')->defaults('_config', [
                'redirect' => 'admin.channels.index'
            ])->name('admin.channels.store');

            Route::get('/channels/edit/{id}', 'Webkul\Core\Http\Controllers\ChannelController@edit')->defaults('_config', [
                'view' => 'admin::settings.channels.edit'
            ])->name('admin.channels.edit');

            Route::put('/channels/edit/{id}', 'Webkul\Core\Http\Controllers\ChannelController@update')->defaults('_config', [
                'redirect' => 'admin.channels.index'
            ])->name('admin.channels.update');

            Route::get('/channels/delete/{id}', 'Webkul\Core\Http\Controllers\ChannelController@destroy')->name('admin.channels.delete');


            // Admin Profile route
            Route::get('/account', 'Webkul\User\Http\Controllers\AccountController@edit')->defaults('_config', [
                'view' => 'admin::account.edit'
            ])->name('admin.account.edit');

            Route::put('/account', 'Webkul\User\Http\Controllers\AccountController@update')->name('admin.account.update');

            // Admin Store Front Settings Route
            Route::get('/slider','Webkul\Shop\Http\Controllers\SliderController@index')->defaults('_config',[
                'view' => 'admin::settings.sliders.index'
            ])->name('admin.sliders.index');

            // Admin Store Front Settings Route

            //slider create
            Route::get('slider/create','Webkul\Shop\Http\Controllers\SliderController@create')->defaults('_config',[
                'view' => 'admin::settings.sliders.create'
            ])->name('admin.sliders.create');

            Route::post('slider/create','Webkul\Shop\Http\Controllers\SliderController@store')->defaults('_config',[
                'redirect' => 'admin::sliders.index'
            ])->name('admin.sliders.store');

            //slider edit
            Route::get('slider/edit/{id}','Webkul\Shop\Http\Controllers\SliderController@edit')->defaults('_config',[
                'view' => 'admin::settings.sliders.edit'
            ])->name('admin.sliders.edit');

            Route::post('slider/edit/{id}','Webkul\Shop\Http\Controllers\SliderController@update')->defaults('_config',[
                'redirect' => 'admin::sliders.index'
            ])->name('admin.sliders.update');

            //destroy a slider item
            Route::get('slider/delete/{id}', 'Webkul\Shop\Http\Controllers\SliderController@destroy');

            //tax routes
            Route::get('/tax-categories', 'Webkul\Tax\Http\Controllers\TaxController@index')->defaults('_config', [
                'view' => 'admin::tax.tax-categories.index'
            ])->name('admin.tax-categories.index');

            // tax category routes
            Route::get('/tax-categories/create', 'Webkul\Tax\Http\Controllers\TaxCategoryController@show')->defaults('_config', [
                'view' => 'admin::tax.tax-categories.create'
            ])->name('admin.tax-categories.show');

            Route::post('/tax-categories/create', 'Webkul\Tax\Http\Controllers\TaxCategoryController@create')->defaults('_config', [
                'redirect' => 'admin.tax-categories.index'
            ])->name('admin.tax-categories.create');

            Route::get('/tax-categories/edit/{id}', 'Webkul\Tax\Http\Controllers\TaxCategoryController@edit')->defaults('_config', [
                'view' => 'admin::tax.tax-categories.edit'
            ])->name('admin.tax-categories.edit');

            Route::put('/tax-categories/edit/{id}', 'Webkul\Tax\Http\Controllers\TaxCategoryController@update')->defaults('_config', [
                'redirect' => 'admin.tax-categories.index'
            ])->name('admin.tax-categories.update');
            //tax category ends

            //tax rate
            Route::get('tax-rates', 'Webkul\Tax\Http\Controllers\TaxRateController@index')->defaults('_config', [
                'view' => 'admin::tax.tax-rates.index'
            ])->name('admin.tax-rates.index');

            Route::get('tax-rates/create', 'Webkul\Tax\Http\Controllers\TaxRateController@show')->defaults('_config', [
                'view' => 'admin::tax.tax-rates.create'
            ])->name('admin.tax-rates.show');

            Route::post('tax-rates/create', 'Webkul\Tax\Http\Controllers\TaxRateController@create')->defaults('_config', [
                'redirect' => 'admin.tax-rates.index'
            ])->name('admin.tax-rates.create');

            Route::get('tax-rates/edit/{id}', 'Webkul\Tax\Http\Controllers\TaxRateController@edit')->defaults('_config', [
                'view' => 'admin::tax.tax-rates.edit'
            ])->name('admin.tax-rates.store');

            Route::put('tax-rates/update/{id}', 'Webkul\Tax\Http\Controllers\TaxRateController@update')->defaults('_config', [
                'redirect' => 'admin.tax-rates.index'
            ])->name('admin.tax-rates.update');
            //tax rate ends
        });
    });
});
