<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Arr;

class UpdateProductService
{
    /**
     * @param Product $product
     * @param array  $data
     *
     * @return Product
     */
    public function run(Product $product, array $data = []): Product
    {
        $product->update($data);

        $product->categories()->sync(Arr::get($data, 'categories'));

        $product->status()->associate(Arr::get($data, 'status_id'));

        $product->save();

        return $product;
    }
}
