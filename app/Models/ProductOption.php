<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    use HasFactory;
    protected $fillable = [
        "option_id",
        "product_id",
        "color_id",
        "is_required"
    ];
    protected $casts = [
        'option_id' => 'integer',
        'product_id' => 'integer',
        'color_id' => 'integer'
    ];
    public function option()
    {
        return $this->hasOne(Option::class,"id","option_id");
    }
    public function color()
    {
        return $this->hasOne(Color::class,"id","color_id");
    }
}
