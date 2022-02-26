<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DebitNoteRequest extends FormRequest
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
            "bill_id" => "required",
            "date" => "required",
            "amount" => "required",
            "account_number" => "required",
            "description" => "required",
            "wing_id" => "required",
        ];
    }
}
