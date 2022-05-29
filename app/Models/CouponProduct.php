<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        "coupon_id",
        "product_id",
    ];
    protected $casts = [
        'category_id' => 'integer',
        "product_id" => 'integer',
    ];
}
