<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
            'title' => 'required|string',
            'date_start' => 'required|date',
            'date_end' => 'nullable|date',
            'hour_start' => 'nullable',
            'date_end' => 'nullable',
            'hour_end' => 'nullable',
            'description' => 'nullable|string',
            'concluded' => 'nullable|string|in:S,N',
            
            /** Relations */
            'order_id' => 'nullable',
            
        ];
    }
}
