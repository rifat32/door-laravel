<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Citizen extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        "union_id",
        "ward_id",
        "village_id",
        "post_office_id",
        "upazila_id",
        "district_id",

        "holding_no",
        "thana_head_name",
        "thana_head_religion",
        "thana_head_gender",
        "thana_head_occupation",
        "mobile",
        "guardian",
        "c_mother_name",
        "nid_no",
        "is_tubewell",
        "latrin_type",
        "type_of_living",
        "type_of_organization",
        "previous_due",
        "tax_amount",
        "male",
        "female",
        "annual_price",
        "gov_advantage",
        "image",
        "current_year",
        "raw_house",
        "half_building_house",
        "building_house"
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
