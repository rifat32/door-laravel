<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VariationTemplateRequest extends FormRequest
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
            "name" => "required|numeric",
            "variation_value_template"      => "required|array",
            "variation_value_template.*.name"      => "required|numeric",
        ];
    }
}
