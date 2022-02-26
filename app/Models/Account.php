<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ChartOfAccount;

class Account extends Model
{
    use HasFactory;
    protected $fillable = [
        "name"
    ];
    public function types()
    {
        return $this->hasMany(AccountType::class, "account_id", "id");
    }
    public function ChartOfAccounts()
    {
        return $this->hasMany(ChartOfAccount::class, "account_id", "id");
    }
}
