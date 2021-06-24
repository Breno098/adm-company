<?php

namespace App\Http\Controllers\API;

use App\Models\Item;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ItemController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item = Item::where('active', true)
                    ->with('status')
                    ->orderby('name')
                    ->get();

        return $this->sendResponse($item, 'Items retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'type' => 'required|in:service,product'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $item = Item::create($input);

        $item->categories()->sync($input['categories']);

        if($status_id = $input['status_id']){
            $status = Status::find($status_id);
            $item->status()->associate($status);
            $item->save();
        }

        return $this->sendResponse($item, 'Item created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Item::where('active', true)
                    ->with('status')
                    ->where('id', $id)
                    ->first();

        if (is_null($item)) {
            return $this->sendError('Item not found.');
        }

        $item->load(['categories' => function($query) {
            $query->where('active', true);
        }]);

        return $this->sendResponse($item, 'Item retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Int $id)
    {
        $item = Item::find($id);

        if (is_null($item)) {
            return $this->sendError('Item not found.');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'type' => 'required|in:service,product'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $item->update($input);

        $item->categories()->sync($input['categories']);

        if($status_id = $input['status_id']){
            $status = Status::find($status_id);
            $item->status()->associate($status);
            $item->save();
        }

        return $this->sendResponse($item, 'Item updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Int $id)
    {
        $item = Item::find($id);

        if (is_null($item)) {
            return $this->sendError('Item not found.');
        }

        $item->active = false;
        $item->save();

        return $this->sendResponse([], 'Item deleted successfully.');
    }
}
