<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class College extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];

    public function users(){
        $this->belongsToMany(User::class,'user_manage_colleges');
    }

    public function departments(){
        return $this->hasMany(Department::class);
    }
}
