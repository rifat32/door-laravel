<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        "order_id",
     "price",
        "qty",
        "product_id",
        "coupon_discount_type",
        "coupon_discount_amount",
         "selectedHeight",
        "selectedWidth",
        "selectedProductColor",
        "is_hinge_holes",
        "is_custom_size",
        "is_extra_holes",
        "orientation_id",
        "hinge_holes_from_top",
        "hinge_holes_from_bottom",
        "extra_holes_direction_id",
        "extra_holes_value",
        "custom_height",
        "custom_width",
        "selected_length",
    ];
    protected $casts = [
        'order_id' => 'integer',

    ];
    public function options()
    {
        return $this->hasMany(OrderDetailOption::class,"order_detail_id","id");
    }
    public function product()
    {
        return $this->hasOne(Product::class,"id","product_id");
    }
    public function product_variation()
    {
        return $this->hasOne(ProductVariation::class,"id","selectedHeight");
    }
    public function variation()
    {
        return $this->hasOne(Variation::class,"id","selectedWidth");
    }
    public function color()
    {
        return $this->hasOne(Color::class,"name","selectedProductColor");
    }




}
