<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\Product\IndexService;
use App\Services\Product\StoreService;
use App\Services\Product\UpdateService;

class ProductController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('index_product');

        $item = IndexService::run(
            $request->query(), 
            $request->get('relations', [ 'status' ]),
            $request->get('itemsPerPage', false),
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
        $this->authorize('add_product');

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
        $this->authorize('show_product');

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
        $this->authorize('update_product');

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
        $this->authorize('delete_product');

        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }

}
