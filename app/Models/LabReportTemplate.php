<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class LabReportTemplate extends Model
{
    use HasFactory, SoftDeletes;
    // "name" => "required",
    // "template" => "required"
    protected $fillable = [
        "name",
        "template"
    ];
}
