<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class primarySchoolGraduationYear extends Model
{
    public $timestamps = false;
    protected $fillable = ['start_year','end_year'];

    public function students(){
        return $this->hasMany(Student::class,'primary_school_graduation_year');
    }

}
