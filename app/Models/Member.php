<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "father_name",
        "mother_name",
        "village_name",
        "post_office",
        "upazila",
        "district",
        "nid",
        "image",
        "citizen_id",
     ];
     protected $casts = [
         'citizen_id' => 'integer',
     ];

     public function citizen()
     {
         return $this->hasOne(Citizen::class, 'id', 'citizen_id')->withTrashed();
     }


}
