<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VariationValueTemplate extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "variation_template_id"

     ];
     protected $casts = [
         "variation_template_id"=> 'integer'
     ];
     public function variation_template()
     {
         return $this->belongsTo(VariationTemplate::class, 'variation_template_id', 'id');
     }

}
