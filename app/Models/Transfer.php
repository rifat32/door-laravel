<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bank;

class Transfer extends Model
{
    use HasFactory;
    protected $fillable = [
        "sending_wing_id",
        "recieving_wing_id",
        "sending_account_number",
        "recieving_account_number",
        "sending_bank_id",
        "recieving_bank_id",
        "amount",
    ];
    protected $casts = [
        'amount' => 'integer',
        'recieving_wing_id' => 'integer',
        'sending_wing_id' => 'integer',
        'recieving_bank_id' => 'integer',
        'sending_bank_id' => 'integer',
    ];
    public function senderBank()
    {
        return $this->hasOne(Bank::class, 'id', 'sending_bank_id');
    }
    public function recieverBank()
    {
        return $this->hasOne(Bank::class, 'id', 'recieving_bank_id');
    }
}
