<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebitNote extends Model
{
    use HasFactory;
    // $table->integer("bill_id");
    // $table->integer("amount");
    // $table->string("account_number");
    // $table->date("date");
    // $table->text("description");
    // $table->integer("wing_id");
    // $table->boolean("status")->default(false);
    // $table->integer("bank_id");
    // $table->integer("transaction_id")->nullable();
    protected $fillable = [
        "bill_id",
        "date",
        "amount",
        "account_number",
        "description",
        "wing_id",
        "bank_id"
    ];
    protected $casts = [
        'amount' => 'integer',
        'wing_id' => 'integer',
        'status' => 'boolean',
        "transaction_id" => 'integer',
        "bank_id" => "integer",
        "bill_id" => "integer"
    ];
    public function wing()
    {
        return $this->hasOne(Wing::class, 'id', 'wing_id')->withTrashed();
    }
    public function bill()
    {
        return $this->hasOne(Bill::class, 'id', 'bill_id');
    }
}
