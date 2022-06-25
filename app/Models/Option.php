<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
     ];
     public function option_value_template()
     {
         return $this->hasMany(OptionValue::class, 'option_id', 'id');
     }
}
