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
        "style_id",
        "sku",
         "image",
        "description",
        "status",
        "is_featured",
        "length_lower_limit",
        "length_upper_limit",
        "length_is_required"
    ];
    protected $casts = [
        'category_id' => 'integer',
        "style_id" => 'integer'
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
    public function style()
    {
        return $this->belongsTo(Style::class,"style_id","id");
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class,"product_id","id");
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class,"product_id","id");
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class,"product_id","id");
    }




}
