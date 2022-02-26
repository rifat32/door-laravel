<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillRequest extends FormRequest
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
            "vendor" => "required",
            "bill_date" => "required",
            "due_date" => "required",
            "category" => "required",
            "order_number" => "required",
            "discount_apply" => "required",
            "wing_id" => "required",
        ];
    }
}
