<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "type",
        "url",
        "parent_id",
        "is_active",
    ];
    protected $casts = [
        'parent_id' => 'integer',
    ];
    public function children()
    {
        return $this->hasMany(Menu::class,"parent_id","id");
    }
}
