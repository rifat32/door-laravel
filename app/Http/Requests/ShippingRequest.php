<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShippingRequest extends FormRequest
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

            "country_id" => "nullable|numeric",
            "state_id" => "nullable|numeric",
        "rate_name"=> "required",
        "price"=> "required|numeric",
        "based_on" => "nullable",
        "minimum" => "nullable|numeric",
        "maximum"=> "nullable|numeric",
        ];
    }
}
