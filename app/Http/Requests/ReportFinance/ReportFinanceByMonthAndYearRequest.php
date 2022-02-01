<?php

namespace App\Http\Requests\ReportFinance;

use Illuminate\Foundation\Http\FormRequest;

class ReportFinanceByMonthAndYearRequest extends FormRequest
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
            'year' => 'required|integer',
            'month' => 'required|integer'
        ];
    }
}
