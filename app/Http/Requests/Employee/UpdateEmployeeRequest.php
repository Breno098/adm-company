<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
            'name' => 'required|string',
            'cpf' => 'nullable',
            'rg' => 'nullable',
            'birth_date' => 'nullable|date',
            'salary' => 'nullable',
            'admission_date' => 'nullable|date',
            'resignation_date' => 'nullable|date',
            'position_id' => 'nullable|int',

             /** Relations */
            'contacts' => 'nullable|array',
            'addresses' => 'nullable|array',
        ];
    }
}
