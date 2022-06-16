<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            "id" => "required",
            "name" => "required",
            "type" => "required",
            "category_id" => "required",
            "style_id" => "required",
            "image" => "nullable",
            "images"   =>"nullable|array",
            "description" => "required",
            "price" => "required_if:type,single",
            "qty" => "required_if:type,single",
            "sku" => "required",
            "is_featured"   =>"required|boolean",
            "status" => "required",
            "variation" => "required_if:type,variable|array",
            "variation.*.color_id"  => "nullable",
            "variation.*.variation_template_id"  => "required_if:type,variable",
            "variation.*.variation_value_template"  => "required_if:type,variable|array",
            "variation.*.variation_value_template.*.price"  => "required_if:type,variable|not_in:0,0",
            "variation.*.variation_value_template.*.qty"  => "required_if:type,variable|not_in:0,0",
            "variation.*.variation_value_template.*.name"  => "required_if:type,variable",
            "variation.*.variation_value_template.*.sub_sku"  => "nullable",
            "variation.*.variation_value_template.*.id"  => "required_if:type,variable",
            "colors" => "required_if:type,variable|array",
            "colors.*.color_id"  => "required_if:type,variable",
            "colors.*.color_image"  => "required_if:type,variable",
            "colors.*.is_variation_specific"  => "required_if:type,variable|boolean",
        ];
    }
}
