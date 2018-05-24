<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    public $table = 'rooms';
    public $timestamps = true;
    
    protected $fillable = [
        'name', 
        'description', 
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function sensorModules()
    {
        return $this->hasMany('App\SensorModule');
    }

    public function getFillable()
    {
        return $this->fillable;
    }
}
