<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
class Product extends Model
{
    use HasFactory, SoftDeletes,HasSlug;
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
        "length_is_required",
        
        "height_lower_limit",
        "height_upper_limit",
        "height_is_required",

        "width_lower_limit",
        "width_upper_limit",
        "width_is_required",

        "slug",
        "panels"
    ];
    protected $casts = [
        'category_id' => 'integer',
        "style_id" => 'integer'
    ];

    public function product_variations()
    {
        return $this->hasMany(ProductVariation::class, "product_id", "id");
    }

    public function variations()
    {
        return $this->hasMany(Variation::class, "product_id", "id");
    }

    public function category()
    {
        return $this->belongsTo(Category::class, "category_id", "id");
    }
    public function style()
    {
        return $this->belongsTo(Style::class, "style_id", "id");
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, "product_id", "id");
    }

    public function colors()
    {
        return $this->hasMany(ProductColor::class, "product_id", "id");
    }

    public function options()
    {
        return $this->hasMany(ProductOption::class, "product_id", "id");
    }

/*     public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    } */
        /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
    
}
