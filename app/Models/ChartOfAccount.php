<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AccountType;

class ChartOfAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        "name",
        "code",
        "account_id",
        "type_id",
        "is_enabled",
        "description",
        "wing_id",
    ];
    protected $casts = [
        "account_id" => "integer",
        "type_id" => "integer",
        "is_enabled" => "boolean",
        "wing_id" => "integer",
    ];
    public function AccountType()
    {
        return $this->belongsTo(AccountType::class, "type_id", "id");
    }
}
