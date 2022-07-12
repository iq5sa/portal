<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    public $timestamps = false;
    protected $fillable = ['student_id','title','number','date','description','path','document_type_id'];

    public function student(){
        return $this->belongsTo(Student::class,'id','student_id');
    }
}
