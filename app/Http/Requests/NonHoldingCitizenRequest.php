<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NonHoldingCitizenRequest extends FormRequest
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
                "ward_id"      =>"required",
                "village_id"      =>"required",
                "post_office_id"      =>"required",
                "upazila_id"      =>"required",
                "district_id"      =>"required",

        "institute_name" =>"required",
        "business_address" =>"required",
        "license_no" =>"required",
        "license_user_name" =>"required",
        "guardian",
        "mother_name" =>"required",
        "nid" =>"required",
        "mobile" =>"required",
        "parmanent_address" =>"required",
        "type" =>"required",
        "current_year" =>"required",
        "tax_amount" =>"required|numeric",
        "previous_due" =>"required|numeric",
        "holding_no" =>"required",
                ];

    }
}
