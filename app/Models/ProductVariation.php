<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "product_id",
        "variation_template_id"
    ];
    protected $casts = [
        'product_id' => 'integer',
        'variation_template_id' => 'integer',

    ];
    public function variations()
    {
        return $this->hasMany(Variation::class,"product_variation_id","id");
    }
    public function variation_template()
    {
        return $this->belongsTo(VariationTemplate::class,"variation_template_id","id");
    }
}
