<?php

namespace App\Http\Requests\EmployeeReceipt;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeReceiptRequest extends FormRequest
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
            'date_start' => 'required|date',
            'date_end' => 'required|date',
            'amount' => 'required',
            'discount_amount' => 'nullable',
            'comments' => 'nullable|string',
            'paid' => 'nullable',
            'employee_id' => 'required|int',
        ];
    }
}
