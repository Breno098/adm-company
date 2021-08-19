<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\ItemRequest;
use App\Models\Item;
use App\Services\Item\DestroyService;
use App\Services\Item\IndexActiveService;
use App\Services\Item\ShowService;
use App\Services\Item\StoreService;
use App\Services\Item\UpdateService;

class ItemController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $item = IndexActiveService::run(
            $request->query(), 
            $request->get('relations', [ 'status' ]),
            $request->get('pagination', false),
            $request->get('itemsPerPage', 20),
        );

        return $this->sendResponse($item, 'Items retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {
        $data = $request->validated();

        $item = StoreService::run($data);

        return $this->sendResponse($item, 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return $this->sendResponse($item, 'Item retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRequest $request, Item $item)
    {
        $data = $request->validated();

        $item = UpdateService::run($item, $data);

        return $this->sendResponse($item, 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return $this->sendResponse([], 'Item deleted successfully.');
    }

}
