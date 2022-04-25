<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "category_id",
        "code",
        "discount",
         "expire_date",
        "is_active",
    ];
    protected $casts = [
        'category_id' => 'integer'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class,"category_id","id");
    }
}
