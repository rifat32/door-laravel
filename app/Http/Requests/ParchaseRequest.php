<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParchaseRequest extends FormRequest
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
            "supplier" => "required",
            "reference_no" => "required",
            "purchase_status" => "required",
            "product_id" => "required",
            "payment_method" => "required",
            "quantity" => "required",
            "wing_id" => "required",
            "account_number" => "required_if:payment_method,bank transfer"
        ];
    }
}
