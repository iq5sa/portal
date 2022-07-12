<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseType extends Model
{
    public $timestamps = false;
    protected $fillable = ['type'];
}
