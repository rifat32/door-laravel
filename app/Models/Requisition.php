<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;
    protected $fillable = [
        "supplier",
        "reference_no",
        "purchase_status",
        "product_id",
        "status_type",
        "quantity",
        "wing_id",
        "account_number",
        "bank_id",
        "user_id"
    ];
    protected $casts = [
        'product_id' => 'integer',
        'quantity' => 'integer',
        'wing_id' => 'integer',
        "user_id" => "integer"
        // 'bank_id' => 'integer',
        // "transaction_id" => 'integer'
    ];
    public function wing()
    {
        return $this->hasOne(Wing::class, 'id', 'wing_id')->withTrashed();
    }
    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
    public function scopeAmount($query)
    {
        $product = $this->product()->first();
        if ($product) {
            return $product->price * $this->quantity;
        }
        return 0;
    }
}
