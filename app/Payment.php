<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'id',
        'payment_id',
        'payment_date',
        'payment_method',
        'payment_amount',
        'fees_id',
        'student_id',
        'revert',
        'user_id',
        'description',
        'cheque_number',
        'cheque_date',

    ];

    public $timestamps = false;
}
