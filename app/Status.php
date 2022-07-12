<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $timestamps = true;
    protected $fillable =
        [
            'student_id',
            'student_class_id',
            'academic_status_id',
            'is_active',
            'administrative_order_id',
            'description',
            'has_hold_subject',
            'hold_class_id',
            'holdin_order_id',
            'has_fail',
            'fail_class_id',
            'fail_order_id',
            'previous_fail_id'
        ];

    public function administrative_orders(){
        return $this->hasMany(AdministrativeOrder::class,'academic_status_id');
    }
}


