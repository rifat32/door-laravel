<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NonHoldingCitizen extends Model
{

    use HasFactory,SoftDeletes;
  
    protected $fillable = [
        "union_id",
        "ward_id",
        "village_id",
        "post_office_id",
        "upazila_id",
        "district_id",

        "institute_name",
        "business_address",
        "license_no",
        "license_user_name",
        "guardian",
        "mother_name",
        "nid",
        "mobile",
        "parmanent_address",
        "type",
        "current_year",
        "tax_amount",
        "previous_due",
        "holding_no",
    ];

    protected $casts = [
        "union_id"=> 'integer',
       "ward_id"=> 'integer',
       "village_id"=> 'integer',
       "post_office_id"=> 'integer',
       "upazila_id"=> 'integer',
       "district_id"=> 'integer',
    ];
    public function union()
    {
        return $this->hasOne(Union::class, 'id', 'union_id')->withTrashed();
    }
    public function ward()
    {
        return $this->hasOne(Ward::class, 'id', 'ward_id')->withTrashed();
    }
    public function village()
    {
        return $this->hasOne(Village::class, 'id', 'village_id')->withTrashed();
    }
    public function postOffice()
    {
        return $this->hasOne(PostOffice::class, 'id', 'post_office_id')->withTrashed();
    }
    public function upazila()
    {
        return $this->hasOne(Upazila::class, 'id', 'upazila_id')->withTrashed();
    }
    public function district()
    {
        return $this->hasOne(District::class, 'id', 'district_id')->withTrashed();
    }
}
