<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'technical_visit' => 'nullable|date',
            'discount_amount' => 'nullable',
            'discount_percentage' => 'nullable',
            'warranty_days' => 'nullable|integer',
            'warranty_conditions' => 'nullable|string',
            'installments' => 'nullable',
            'comments' => 'nullable|string'
        ];
    }
}
