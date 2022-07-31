<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
        "billing_address",
        "billing_address2",
        "city",
        "zipcode",
        "user_id",
        "fname",
        "lname",
        "cname",
        "country",
        "state",
        "phone",
        "is_default",

    ];
}
