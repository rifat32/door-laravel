<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitizenTax extends Model
{
    use HasFactory;
    protected $fillable = [
        "note",
        "amount",
        "current_year",
        "union_id",
        "citizen_id",
        "ward_id"
     ];
     protected $casts = [
         'union_id' => 'integer',
         'citizen_id' => 'integer',
         'ward_id' => 'integer',
     ];
     public function union()
     {
         return $this->hasOne(Union::class, 'id', 'union_id')->withTrashed();
     }
     public function citizen()
     {
         return $this->hasOne(Citizen::class, 'id', 'citizen_id')->withTrashed();
     }
     public function ward()
     {
         return $this->hasOne(Ward::class, 'id', 'ward_id')->withTrashed();
     }

}
