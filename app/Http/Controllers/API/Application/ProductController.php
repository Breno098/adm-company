<?php

namespace App\Http\Controllers\API\Application;

use App\Http\Controllers\API\Bases\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\Product\IndexService;
use App\Services\Product\StoreService;
use App\Services\Product\UpdateService;

class ProductController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('product_index');

        $item = IndexService::run(
            $request->query(), 
            $request->get('relations', []),
            $request->get('orderBy'),
            $request->get('itemsPerPage'),
        );

        return $this->sendResponse($item, 'Products retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $this->authorize('product_add');

        $data = $request->validated();

        $item = StoreService::run($data);

        return $this->sendResponse($item, 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $this->authorize('product_show');

        $product->load(['categories']);
        
        return $this->sendResponse($product, 'Product retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, Product $product)
    {
        $this->authorize('product_update');

        $data = $request->validated();

        $product = UpdateService::run($product, $data);

        return $this->sendResponse($product, 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->authorize('product_delete');

        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }

}
