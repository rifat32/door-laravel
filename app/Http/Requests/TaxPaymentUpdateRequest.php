<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxPaymentUpdateRequest extends FormRequest
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
            "payment_for" => "required",
            "amount" => "required",
            "current_year" => "required",
            "union_id" => "required",
            "citizen_id" => "required",
            "method_id" => "required"
        ];
    }
}
