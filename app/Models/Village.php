<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Village extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "name",
        "ward_id",
        "union_id"
     ];
     protected $casts = [
         'union_id' => 'integer',
         'ward_id' => 'integer',
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
