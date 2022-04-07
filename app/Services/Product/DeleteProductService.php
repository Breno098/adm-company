<?php

namespace App\Services\Product;

use App\Models\Product;
use Exception;

class DeleteProductService
{
    /**
     * @param Product $product
     *
     * @return null|bool
     */
    public function run(Product $product): bool
    {
        if($product->orders->count()) {
            throw new Exception("Este produto tem vinculos com um ou mais pedidos. Para deletar o produto, delete os pedidos vinculados.");
        }

        return $product->delete();
    }
}
