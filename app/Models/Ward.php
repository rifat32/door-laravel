<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ward extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
       "ward_no",
       "union_id"
    ];
    protected $casts = [
        'union_id' => 'integer'
    ];
    public function union()
    {
        return $this->hasOne(Union::class, 'id', 'union_id')->withTrashed();
    }
}
