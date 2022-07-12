<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TempPayment extends Model
{
    //

    protected $fillable = ['payment_id','student_name','payment_date','payment_amount','note'];

}
