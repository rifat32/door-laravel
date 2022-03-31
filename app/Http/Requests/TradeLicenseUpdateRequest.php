<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TradeLicenseUpdateRequest extends FormRequest
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
            "ward_id" => "required",

            "institute" => "required",
            "owner" => "required",
            "guadian" => "required",
            "mother_name" => "required",
            "present_addess" => "required",
            "license_no" => "required",
            "business_type" => "required",
            "permanent_addess" => "required",
            "fee_des" => "required",
            "mobile_no" => "required",
            "fee" => "required",
            "vat" => "required",
            "nid" => "required",
            "expire_date" => "required",
            "total" => "required",
            "vat_des" => "required",
            "current_year" => "required"
        ];
    }
}
