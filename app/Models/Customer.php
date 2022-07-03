<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        "fname",
        "lname",
        "cname",
        "phone",
        "email",
        "total_order",
        "type",
        "billing_address",
        "billing_address2",
        "city",
        "zipcode"


    ];
}
