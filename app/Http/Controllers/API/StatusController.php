<?php

namespace App\Http\Controllers\API;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StatusController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statuses = Status::all();
        return $this->sendResponse($statuses, 'Statuses retrieved successfully.');
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
            'type' => 'required|in:order, client, appointment, item'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $status = Status::create($input);

        return $this->sendResponse($status, 'Status created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $status = Status::find($id);

        if (is_null($status)) {
            return $this->sendError('Status not found.');
        }

        return $this->sendResponse($status, 'Status retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Status $status)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'type' => 'required|in:order, client, appointment, item'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $status->name = $input['name'];
        $status->save();

        return $this->sendResponse($status, 'Status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        $status->delete();

        return $this->sendResponse([], 'Status deleted successfully.');
    }
}
