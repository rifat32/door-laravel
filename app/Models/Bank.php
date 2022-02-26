<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;
    protected $table = "banks";
    protected $fillable = [
        "name",
        "account_number",
        "wing_id"

    ];
    protected $casts = [
        '"wing_id"' => 'integer',
    ];

    public function wing()
    {
        return $this->hasOne(Wing::class, 'id', 'wing_id')->withTrashed();
    }
    // get bank by account number and wing id
    public function scopeGetBank($query, $wing_id, $account_number)
    {
        $bank =  $query->where(["wing_id" => $wing_id, "account_number" => $account_number])->first();
        if (!$bank) {
            return false;
        }
        return $bank;
    }
}
