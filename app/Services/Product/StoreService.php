<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Arr;

class StoreService
{
    /**
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(array $data = [])
    {
        $product = Product::create($data);

        $product->categories()->sync(Arr::get($data, 'categories'));

        $product->status()->associate(Arr::get($data, 'status_id'));

        $product->save();

        return $product;
    }
}
