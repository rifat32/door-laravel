<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chairman extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "union_id",
        "name",
        "nid",
        "mobile",
        "pro_image",
        "sign_image",
        "address",
    ];

    protected $casts = [
        "union_id"=> 'integer',
    ];
    public function union()
    {
        return $this->hasOne(Union::class, 'id', 'union_id')->withTrashed();
    }
}
