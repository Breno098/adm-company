<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'description' => 'nullable|string',
            'type' => 'nullable|string',
            'amount' => 'nullable',
            'amount_paid' => 'nullable',
            'execution_date' => 'nullable|date',
            'discount_amount' => 'nullable',
            'discount_percentage' => 'nullable',
            'warranty_days' => 'nullable|integer',
            'warranty_conditions' => 'nullable|string',
            'installments' => 'nullable',
            'comments' => 'nullable|string',
            'technical_visit_date' => 'nullable|date',
            'technical_visit_time' => 'nullable',
            'status' => 'nullable|string',

            /** Relations */
            'client_id' => 'required',
            'order_id' => 'nullable',
            'address_id' => 'nullable',
            'products' => 'nullable|array',
            'services' => 'nullable|array',
            'payments' => 'nullable|array',
            'form_of_payments' => 'nullable|array',
            'installments' => 'nullable|array',
        ];
    }
}
