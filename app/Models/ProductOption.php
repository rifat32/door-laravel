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
    ];
    protected $casts = [
        'option_id' => 'integer',
        'product_id' => 'integer'
    ];
    public function option()
    {
        return $this->hasOne(Option::class,"id","option_id");
    }
}
