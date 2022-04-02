<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonCitizenTax extends Model
{
    use HasFactory;
    protected $fillable = [
        "note",
        "amount",
        "current_year",
        "union_id",
        "non_citizen_id",
        "ward_id"
     ];
     protected $casts = [
         'union_id' => 'integer',
         'non_citizen_id' => 'integer',
         'ward_id' => 'integer',
     ];
     public function union()
     {
         return $this->hasOne(Union::class, 'id', 'union_id')->withTrashed();
     }
     public function noncitizen()
     {
         return $this->hasOne(NonHoldingCitizen::class, 'id', 'non_citizen_id')->withTrashed();
     }
     public function ward()
     {
         return $this->hasOne(Ward::class, 'id', 'ward_id')->withTrashed();
     }

}
