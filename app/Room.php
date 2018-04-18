<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public $timestamps = true;
    
    protected $fillable = [
        'username', 
        'password', 
        'name', 
        'phone', 
        'isAdmin'
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function SensorModules()
    {
        return $this->belongsToMany('App\SensorModule');
    }

    public function getFillable()
    {
        return $this->$fillable;
    }
}
