<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxPayment extends Model
{
    use HasFactory;


    protected $fillable = [
        "payment_for",
        "amount",
        "current_year",
        "union_id",
        "citizen_id",
        "method_id"
     ];
     protected $casts = [
         'union_id' => 'integer',
         'citizen_id' => 'integer',
         'method_id' => 'integer',
     ];
     public function union()
     {
         return $this->hasOne(Union::class, 'id', 'union_id')->withTrashed();
     }
     public function citizen()
     {
         return $this->hasOne(Citizen::class, 'id', 'citizen_id')->withTrashed();
     }
     public function method()
     {
         return $this->hasOne(Method::class, 'id', 'method_id')->withTrashed();
     }
}
