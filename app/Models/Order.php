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
        "country",
        "billing_address",
        "billing_address2",
        "city",
        "zipcode",
        "phone",
        "email",
        "additional_info",
        "payment_option"
    ];
}
