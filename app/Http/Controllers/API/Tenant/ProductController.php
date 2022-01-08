<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\Product\IndexProductService;
use App\Services\Product\StoreProductService;
use App\Services\Product\UpdateProductService;

class ProductController extends BaseApiController
{
    /**
     * @param Request $request
     * @param IndexProductService $indexProductService
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, IndexProductService $indexProductService)
    {
        $this->authorize('product_index');

        $item = $indexProductService->run(
            $request->query(),
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
        );

        return $this->sendResponse($item, 'Products retrieved successfully.');
    }

    /**
     * @param StoreProductRequest $storeProductRequest
     * @param StoreProductService $storeProductService
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $storeProductRequest, StoreProductService $storeProductService)
    {
        $this->authorize('product_add');

        $data = $storeProductRequest->validated();

        $item = $storeProductService->run($data);

        return $this->sendResponse($item, 'Product created successfully.');
    }

    /**
     * @param Product $product
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $this->authorize('product_show');

        $product->load(['categories']);

        return $this->sendResponse($product, 'Product retrieved successfully.');
    }

    /**
     * @param UpdateProductRequest $updateProductRequest
     * @param UpdateProductService $updateProductService
     * @param Product $product
     *
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateProductRequest $updateProductRequest,
        UpdateProductService $updateProductService,
        Product $product
    )
    {
        $this->authorize('product_update');

        $data = $updateProductRequest->validated();

        $product = $updateProductService->run($product, $data);

        return $this->sendResponse($product, 'Product updated successfully.');
    }

    /**
     * @param Product $product
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->authorize('product_delete');

        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }

}
