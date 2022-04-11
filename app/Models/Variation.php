<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "product_id",
        "sub_sku",
        "product_variation_id",
        "price",
        "qty",

    ];
    protected $casts = [
        'product_id' => 'integer',
        'product_variation_id' => 'integer',

    ];
    public function product()
    {
        return $this->belongsTo(Product::class,"product_id","id");
    }


}
