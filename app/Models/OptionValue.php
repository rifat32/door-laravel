<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "option_id"

     ];
     protected $casts = [
         "option_id"=> 'integer'
     ];
     public function option_template()
     {
         return $this->belongsTo(Option::class, 'option_id', 'id');
     }
}
