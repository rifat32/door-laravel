<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CharOfAccountRequest extends FormRequest
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
            "name" => "required",
            "code" => "required",
            "account_id" => "required",
            "type_id" => "required",
            "is_enabled" => "required",
            "description" => "required",
            "wing_id" => "required",
        ];
    }
}
