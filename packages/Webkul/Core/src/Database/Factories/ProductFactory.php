<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webkul\Product\Models\Product;

$factory->define(Product::class, function (Faker $faker) {
    $now = date("Y-m-d H:i:s");
    return [
        'sku'                 => $faker->uuid,
        'created_at'          => $now,
        'updated_at'          => $now,
        'attribute_family_id' => 1,
    ];
});

$factory->state(Product::class, 'simple', [
    'type' => 'simple',
]);

$factory->state(Product::class, 'virtual_nos', [
    'type' => 'virtual_nos',
]);

$factory->state(Product::class, 'downloadable_with_stock', [
    'type' => 'downloadable_with_stock',
]);
