<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public $timestamps = false;
    protected $fillable = ['college_id','name'];
    public function users(){
        $this->belongsToMany(User::class,'user_manage_department');
    }

    public function college(){
        return $this->belongsTo(College::class);
    }
}
