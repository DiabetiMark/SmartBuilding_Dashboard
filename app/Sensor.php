<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    public $timestamps = true;
    public $table = 'sensors';
    protected $fillable = [
        'name',
        'sensorModule_id',
    ];

    public function rooms()
    {
        return $this->belongsTo('App\SensorModule', 'sensorModule_id', 'id');
    }

    public function dataRegisters()
    {
        return $this->hasMany('App\DataRegister', 'sensor_id', 'id');
    }
    
    public function getFillable()
    {
        return $this->fillable;
    }
}
