<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            "billing_address" => "required|string",
            "billing_address2" => "nullable|string",
            "city" => "required|string",
            "zipcode" => "required|string",
            "fname" => "required|string",
            "lname" => "required|string",
            "cname" => "required|string",
            "country_id" => "required|numeric",
            "state_id" => "required|numeric",
            "phone" => "required|string",
            "is_default" => "required|boolean",
        ];
    }
}
