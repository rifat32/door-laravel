<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    // $table->date("date");
    //         $table->string("remarks");
    //         $table->string("status");
    //         $table->unsignedBigInteger("doctor_id");
    //         $table->unsignedBigInteger("patient_id");
    protected $fillable = [
        "date",
        "remarks",
        "status",
        "doctor_id",
        "patient_id",
    ];
    protected $casts = [
        "doctor_id" => 'integer',
        "patient_id" => "integer",
    ];
    public function patient()
    {
        return $this->hasOne(Patient::class, 'id', 'patient_id')->withTrashed();
    }
    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'id', 'doctor_id')->withTrashed();
    }
}
