<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'discount_name',
        'discount_type',
        'discount_amount',
        'discount_description',
    ];
}
