<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferBalanceRequest extends FormRequest
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
            "sending_wing_id" => "required",
            "recieving_wing_id" => "required",
            "sending_account_number" => "required",
            "recieving_account_number" => "required",
            "amount" => "required",
        ];
    }
}
