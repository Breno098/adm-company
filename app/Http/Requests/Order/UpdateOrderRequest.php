<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
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
            'complaint' => 'nullable|string',
            'type' => 'nullable|string',
            'amount' => 'nullable',
            'amount_paid' => 'nullable',
            'execution_date' => 'nullable|date',
            'discount_amount' => 'nullable',
            'discount_percentage' => 'nullable',
            'warranty_days' => 'nullable|integer',
            'warranty_conditions' => 'nullable|string',
            'comments' => 'nullable|string',
            'technical_visit_date' => 'nullable|date',
            'technical_visit_time' => 'nullable',
            'status' => 'nullable|string',
            'accepted_payment_methods' => 'nullable|string',
            'number_of_installments' => 'nullable|integer',
            'work_found' => 'nullable|string',
            'work_done' => 'nullable|string',

            /** Relations */
            'client_id' => 'required',
            'order_id' => 'nullable',
            'address_id' => 'nullable',
            'installments' => 'nullable|array',
            'products' => 'nullable|array',
            'services' => 'nullable|array',
            'payments' => 'nullable|array',
        ];
    }
}
