<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicStatus extends Model
{
    public function order(){
        return $this->belongsTo(AcademicStatus::class,'academic_status_id');
    }
}
