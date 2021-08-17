<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'type' => 'required|in:PF,PJ',
            'document' => 'nullable|string',
            'fantasy_name' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'notes' => 'nullable|string',
            'active' => 'nullable|boolean',
            
            /** Relations */
            'contacts' => 'nullable|array',
            'addresses' => 'nullable|array',
        ];
    }
}
