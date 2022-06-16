<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory;
    protected $fillable = [
        "color_id",
        "product_id",
        "color_image",
        "is_variation_specific"
    ];
    protected $casts = [
        'color_id' => 'integer',
        'product_id' => 'integer'
    ];
    public function color()
    {
        return $this->hasOne(Color::class,"id","color_id");
    }
}
