<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
            'name' => 'required',
            'cnpj' => 'nullable|string',
            'cpf' => 'nullable|string',
            'fantasy_name' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'active' => 'nullable|boolean',

            /** Relations */
            'contacts' => 'nullable|array',
            'addresses' => 'nullable|array',
            'category_id' => 'nullable',
        ];
    }
}
