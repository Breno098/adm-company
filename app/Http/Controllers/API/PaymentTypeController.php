<?php

namespace App\Http\Controllers\API;

use App\Models\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentTypeController extends BaseControllerApi
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paymentTypes = PaymentType::all();
        return $this->sendResponse($paymentTypes, 'PaymentTypes retrieved successfully.');
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
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $paymentType = PaymentType::create($input);

        return $this->sendResponse($paymentType, 'PaymentType created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $paymentType = PaymentType::find($id);

        if (is_null($paymentType)) {
            return $this->sendError('PaymentType not found.');
        }

        return $this->sendResponse($paymentType, 'PaymentType retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentType $paymentType)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $paymentType->name = $input['name'];
        $paymentType->save();

        return $this->sendResponse($paymentType, 'PaymentType updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentType $paymentType)
    {
        $paymentType->delete();

        return $this->sendResponse([], 'PaymentType deleted successfully.');
    }
}
