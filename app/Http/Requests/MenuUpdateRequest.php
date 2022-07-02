<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuUpdateRequest extends FormRequest
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
            "id"=>"required",
            "name" => "required|string",
            "url"      => "required",
            "type"      => "required",
            "is_active"      => "required|boolean",
            "children"      => "required_if:type,dropdown|array",
        ];
    }
}