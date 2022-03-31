<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComplainUpdateRequest extends FormRequest
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
            "complain_no" => "required",
            "year" => "required",
            "complain_date" => "required",
            "applicant_name" => "required",
            "applicant_father_name" => "required",
            "applicant_witness" => "required",
            "applicant_village" => "required",
            "applicant_post_office" => "required",
            "applicant_district" => "required",
            "applicant_thana" => "required",
            "defendant_name" => "required",
            "defendants" => "required",
            "defendant_father_name" => "required",
            "defendant_village" => "required",
            "defendant_post_office" => "required",
            "defendant_district" => "required",
            "defendant_thana" => "required",
            "applicant_mobile" => "required",
            "date" => "required",
            "time" => "required",
            "place" => "required",
            "union_id" => "required",
            "chairman_id" => "required",
            "is_solved"=> "required|boolean"
        ];
    }
}
