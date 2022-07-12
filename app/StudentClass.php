<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentClass extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'academic_year_id',
        'college_id',
        'department_id',
        'level_id',
        'shift_id',
        'type_id',
        'time_id',
        'group_id',
        'batch_id',
        'start_date',
        'end_date',
        'active',
    ];

    public function students(){
        return $this->belongsToMany(Student::class,'statuses','student_class_id','student_id','class_id','id');
    }
}
