<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            "fname" => "required|string",
            "lname" => "required|string",
            "cname" => "nullable|string",
            "billing_address" => "required|string",
            "billing_address2" => "nullable|string",
            "city" => "required|string",
            "zipcode" => "required|string",
            "phone"=> "required|string",
            "email"=> "required|string|unique:users,email",
            "additional_info"=> "nullable|string",
            "create_account"=>"required",

            'password' => 'nullable',
            "cart" => "required|array",
            "order_coupon" => "nullable"

        ];
    }
}
