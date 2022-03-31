<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TradeLicense extends Model
{
    use HasFactory;
    protected $fillable = [
        "union_id",
        "ward_id",

        "institute",
        "owner",
        "guadian",
        "mother_name",
        "present_addess",
        "license_no",
        "business_type",
        "permanent_addess",
        "fee_des",
        "mobile_no",
        "fee",
        "vat",
        "nid",
        "expire_date",
        "total",
        "vat_des",
        "current_year"
    ];

    protected $casts = [
        "union_id"=> 'integer',
       "ward_id"=> 'integer'
    ];
    public function union()
    {
        return $this->hasOne(Union::class, 'id', 'union_id')->withTrashed();
    }
    public function ward()
    {
        return $this->hasOne(Ward::class, 'id', 'ward_id')->withTrashed();
    }
}
