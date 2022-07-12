<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    protected $fillable =
        [
            'academic_year_id',
            'college_id',
            'department_id',
            'required_amount',
            'student_id',
            'class_id',
        ];

    protected $table = 'fees';
    public $timestamps = false;
}
