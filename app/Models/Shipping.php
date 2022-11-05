<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $fillable = [
        "country_id",
        "state_id",
        "rate_name",
        "price",
        "based_on",
        "minimum",
        "maximum",
    ];
    public function state() {
        return $this->hasOne(State::class,"id","state_id");
    }

    public function country() {
        return $this->hasOne(Country::class,"id","country_id");
    }
}
