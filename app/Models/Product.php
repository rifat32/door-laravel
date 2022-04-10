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








    public function wing()
    {
        return $this->hasOne(Wing::class, 'id', 'wing_id')->withTrashed();
    }
}
