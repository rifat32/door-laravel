<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wing extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "wings";
    protected $fillable = [
        "name"
    ];
    // public function wing()
    // {
    //     return $this->hasOne(Wing::class, 'id', 'wing_id');
    // }
}
