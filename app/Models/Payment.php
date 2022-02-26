<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        "date",
        "amount",
        "account_number",
        "description",
        "category",
        "reference",
        "wing_id",
        "bank_id"
    ];
    protected $casts = [
        'amount' => 'integer',
        'wing_id' => 'integer',
        'status' => 'boolean',
        "transaction_id" => 'integer',
        "bank_id" => "integer"
    ];
    public function wing()
    {
        return $this->hasOne(Wing::class, 'id', 'wing_id')->withTrashed();
    }
}
