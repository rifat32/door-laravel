<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationTemplate extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
     ];
     public function variation_value_template()
     {
         return $this->hasMany(VariationValueTemplate::class, 'variation_template_id', 'id');
     }

}
