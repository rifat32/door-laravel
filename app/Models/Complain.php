<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complain extends Model
{
    use HasFactory;
    protected $fillable = [
        "complain_no",
        "year",
        "complain_date",
        "applicant_name",
        "applicant_father_name",
        "applicant_witness",
        "applicant_village",
        "applicant_post_office",
        "applicant_district",
        "applicant_thana",
        "defendant_name",
        "defendants",
        "defendant_father_name",
        "defendant_village",
        "defendant_post_office",
        "defendant_district",
        "defendant_thana",
        "applicant_mobile",
        "date",
        "time",
        "place",
        "is_solved",
        "union_id",
        "chairman_id",
     ];
     protected $casts = [
        "union_id"=> 'integer',
       "chairman_id"=> 'integer',
    ];
    public function union()
    {
        return $this->hasOne(Union::class, 'id', 'union_id')->withTrashed();
    }
    public function chairman()
    {
        return $this->hasOne(Chairman::class, 'id', 'chairman_id')->withTrashed();
    }
}
