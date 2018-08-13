<?php

Route::group(['middleware' => ['web']], function () {

    Route::get('/', 'Webkul\Shop\Http\Controllers\HomeController@index')->defaults('_config', [
        'view' => 'shop::store.home.index'
    ])->name('store.home');

    Route::get('/product', 'Webkul\Shop\Http\Controllers\CategoryController@index')->defaults('_config', [
        'view' => 'shop::store.product.index'
    ]);

});


Route::group(['middleware' => ['web']], function () {
    Route::get('/foo', 'Webkul\Shop\Http\Controllers\HomeController@index1');
});
