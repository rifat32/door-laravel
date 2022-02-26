<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    use HasFactory;
    public function scopeGetBalance($query, $wing_id, $bank_id)
    {
        $balance =  $query->where(["wing_id" => $wing_id, "bank_id" => $bank_id])->first();
        if (!$balance) {
            return false;
        }

        return $balance;
    }
    // update amount
    public function scopeUpdateBalance($query, $wing_id, $bank_id, $amount)
    {

        $query->where(["wing_id" => $wing_id, "bank_id" => $bank_id])->update(["amount" => $amount]);
        return;
    }
}
