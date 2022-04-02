<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CitizenTaxUpdateRequest extends FormRequest
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
            "note" => "required|date",
            "amount" => "required|numeric",
            "current_year" => "required",
            "union_id" => "required|numeric",
            "citizen_id" => "required|numeric",
            "ward_id" => "required|numeric"
        ];
    }
}
