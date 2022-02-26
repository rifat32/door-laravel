<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChartOfAccount;

class AccountType extends Model
{
    use HasFactory;
    protected $fillable = [
        "account_id",
        "name"
    ];
    public function ChartOfAccounts()
    {
        return $this->hasMany(ChartOfAccount::class, "type_id", "id");
    }
}
