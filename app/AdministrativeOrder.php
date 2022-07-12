<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdministrativeOrder extends Model
{
    public $timestamps = false;
    protected $fillable = ['number','date','file','description','path','academic_years_id','academic_status_id','is_active'];

    public function academic_year()
    {
        return $this->belongsTo(AcademicYear::class,'academic_years_id');
    }

    public function academic_status(){
        return $this->hasOne(AcademicStatus::class,'id');
    }
}
