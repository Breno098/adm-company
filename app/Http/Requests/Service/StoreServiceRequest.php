<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
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
            'description' => 'nullable|string',
            'default_value' => 'nullable',
            'unit_measure' => 'nullable|string',
            'brand' => 'nullable|string',
            'cost' => 'nullable',
            'bar_code' => 'nullable|string',
            'warranty_days' => 'nullable|integer',
            'warranty_conditions' => 'nullable|string',

            /** Relations */
            'status_id' => 'nullable',
            'categories' => 'nullable|array',
        ];
    }
}
