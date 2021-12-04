<?php

namespace App\Http\Requests\EmployeeReceipt;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'discount_amount' => 'required',
            'comments' => 'required|string',
            'paid' => 'nullable',
            'employee_id' => 'nullable|int',
        ];
    }
}
