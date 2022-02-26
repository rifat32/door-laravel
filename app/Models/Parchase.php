<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parchase extends Model
{
    use HasFactory;

    protected $fillable = [
        "payment_method",
        "account_number",
        "bank_id",
        "supplier",
        "reference_no",
        "purchase_status",
        "product_id",
        "quantity",
        "is_active",
        "user_id",
        "wing_id",
    ];
    protected $casts = [
        "bank_id"  => 'integer',
        "product_id" => 'integer',
        "quantity"  => 'integer',
        "is_active"  => 'boolean',
        "user_id" => 'integer',
        "wing_id" => 'integer',
    ];

    public function wing()
    {
        return $this->hasOne(Wing::class, 'id', 'wing_id')->withTrashed();
    }
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
    // get single amount from product priice and quantity
    public function scopeAmount($query)
    {
        return $this->product()->where(["id" => $this->product_id])->first()->price * $this->quantity;
    }
}
