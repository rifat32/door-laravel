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
        "country_id",
        "state_id",
        "phone",
        "is_default",

    ];
    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
    public function state()
    {
        return $this->hasOne(State::class, 'id', 'state_id');
    }
}
