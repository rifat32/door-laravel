<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChairmanRequest extends FormRequest
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
        "union_id" => "required",
        "name" => "required",
        "nid" => "required",
        "mobile" => "required",
        "pro_image" => "required",
        "sign_image" => "required",
        "address" => "required",
        ];
    }
}
