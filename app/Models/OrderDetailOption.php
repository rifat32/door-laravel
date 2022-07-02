<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetailOption extends Model
{
    use HasFactory;
    protected $fillable = [
        "option_id",
     "option_value_id",
     "order_detail_id"

    ];
    protected $casts = [
        'option_id' => 'integer',
        'option_value_id' => 'integer',
        'order_detail_id' => "integer"
    ];
    public function option()
    {
        return $this->hasOne(Option::class,"id","option_id");
    }
    public function option_value()
    {
        return $this->hasOne(OptionValue::class,"id","option_value_id");
    }
}
