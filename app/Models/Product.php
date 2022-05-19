<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";
    protected $fillable = [
        "name",
        "type",
        "category_id",
        "sku",
         "image",
        "description",
    ];
    protected $casts = [
        'category_id' => 'integer'
    ];

    public function product_variations()
    {
        return $this->hasMany(ProductVariation::class,"product_id","id");
    }
    public function variations()
    {
        return $this->hasMany(Variation::class,"product_id","id");
    }

    public function category()
    {
        return $this->belongsTo(Category::class,"category_id","id");
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class,"product_id","id");
    }
    public function colors()
    {
        return $this->hasMany(ProductColor::class,"product_id","id");
    }






}
