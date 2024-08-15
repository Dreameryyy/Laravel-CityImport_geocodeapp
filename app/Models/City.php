<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = [
        'name', 
        'mayor_name', 
        'city_hall_address', 
        'phone', 
        'fax', 
        'email', 
        'web_address', 
        'coat_of_arms_path', 
        'latitude', 
        'longitude'
    ];
}
