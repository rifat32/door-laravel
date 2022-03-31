<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CitizenRequest extends FormRequest
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

        "holding_no"      =>"required",
        "thana_head_name"      =>"required",
        "thana_head_religion"      =>"required",
        "thana_head_gender"      =>"required",
        "thana_head_occupation"      =>"required",
        "mobile"      =>"required",
        "guardian"      =>"required",
        "c_mother_name"      =>"required",
        "nid_no"      =>"required",
        "is_tubewell"      =>"required",
        "latrin_type"      =>"required",
        "type_of_living"      =>"required",
        "type_of_organization"      =>"required",
        "previous_due"      =>"required",
        "tax_amount"      =>"required",
        "male"      =>"required",
        "female"      =>"required",
        "annual_price"      =>"required",
        "gov_advantage"      =>"required",
        "image"      =>"required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
        "current_year"      =>"required",
        "raw_house"      =>"required",
        "half_building_house"      =>"required",
        "building_house"      =>"required",
        "member"      =>"required|array",
        "member.name*"      =>"required",
        ];
    }
}
