<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        "fname",
        "lname",
        "cname",
        "billing_address",
        "billing_address2",
        "city",
        "zipcode",
        "phone",
        "email",
        "additional_info",
        "payment_option",
        "status",
        "coupon_id",
        "customer_id",
        "country_id",
        "state_id",
        "shipping",
    ];
    public function order_details()
    {
        return $this->hasMany(OrderDetail::class,"order_id","id");
    }

    public function coupon()
    {
        return $this->hasOne(Coupon::class,"id","coupon_id");
    }
    public function payment()
    {
        return $this->hasOne(OrderPayment::class,"order_id","id");
    }


}
