<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
        "name" =>"required",
        "category_id"   =>"nullable",
        "is_all_category_product"  =>"required|boolean",
        "discount_amount"  =>"required|numeric",
        "discount_type"  =>"required|string",
        "expire_date"  =>"required",
        "is_active"  =>"required|boolean",
        "products"  =>"nullable|array",
        ];
    }
}
