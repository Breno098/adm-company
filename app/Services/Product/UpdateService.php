<?php

namespace App\Services\Product;

use App\Models\Product;
use Illuminate\Support\Arr;

class UpdateService
{
    /**
     * @param  mixed  $id
     * @param  array  $data
     *
     * @return mixed
     */
    static public function run(Product $product, array $data = [])
    {
        $product->update($data);

        $product->categories()->sync(Arr::get($data, 'categories'));

        $product->status()->associate(Arr::get($data, 'status_id'));

        $product->save();

        return $product;
    }
}
