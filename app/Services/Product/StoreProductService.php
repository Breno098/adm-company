<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Arr;

class StoreProductService
{
    /**
     * @param array $data
     *
     * @return Product
     */
    public function run(array $data = []): Product
    {
        $product = Product::create($data);

        $product->categories()->sync(Arr::get($data, 'categories'));

        $product->status()->associate(Arr::get($data, 'status_id'));

        $product->save();

        return $product;
    }
}
