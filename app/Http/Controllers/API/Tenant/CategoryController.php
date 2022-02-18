<?php

namespace App\Http\Controllers\API\Tenant;

use App\Http\Controllers\API\Bases\BaseApiController;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\Category\IndexService;
use App\Services\Category\StoreService;
use App\Services\Category\UpdateService;

class CategoryController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $this->authorize('category_index');

        $categories = IndexService::run(
            $request->query(),
            $request->get('relations', []),
            $request->get('itemsPerPage', false),
        );

        return $this->sendResponse($categories, 'Category retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        // $this->authorize('category_add');

        $data = $request->validated();

        $client = StoreService::run($data);

        return $this->sendResponse($client, 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        // $this->authorize('category_show');

        return $this->sendResponse($category, 'Category retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        // $this->authorize('category_update');

        $data = $request->validated();

        $category = UpdateService::run($category, $data);

        return $this->sendResponse($category, 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // $this->authorize('category_delete');

        $category->delete();

        return $this->sendResponse([], 'Client deleted successfully.');
    }
}
