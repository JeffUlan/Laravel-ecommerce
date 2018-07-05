<?php

Route::group(['middleware' => ['web']], function () {
    
    Route::prefix('admin')->group(function () {
        // Login Routes
        Route::get('/login', 'Webkul\User\Http\Controllers\SessionController@create')->defaults('_config', [
            'view' => 'admin::users.sessions.create'
        ])->name('admin.session.create');

        Route::post('/login', 'Webkul\User\Http\Controllers\SessionController@store')->defaults('_config', [
            'redirect' => 'admin.dashboard.index'
        ])->name('admin.forget-password.store');


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
            
            Route::get('/dashboard', 'Webkul\Admin\Http\Controllers\DashboardController@index')->name('admin.dashboard.index');

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


            // Admin Profile route
            Route::get('/account', 'Webkul\User\Http\Controllers\AccountController@edit')->defaults('_config', [
                'view' => 'admin::account.edit'
            ])->name('admin.account.edit');
        
            Route::put('/account', 'Webkul\User\Http\Controllers\AccountController@update')->name('admin.account.update');
        });
    });
});