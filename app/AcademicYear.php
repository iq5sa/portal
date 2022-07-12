<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $fillable = [
        'start_year',
        'end_year'
    ];

    public function administrative_orders()
    {
        return $this->hasMany(AdministrativeOrder::class);
    }
}
