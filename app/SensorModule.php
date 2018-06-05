<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SensorModule extends Model
{
    public $timestamps = true;
    public $table = 'sensorModules';
    protected $fillable = [
        'moduleName',
        'room_id',
    ];

    public function rooms()
    {
        return $this->belongsTo('App\Room', 'room_id', 'id');
    }

    public function dataRegisters()
    {
        return $this->hasMany('App\DataRegister', 'sensorModule_id', 'id');
    }
    
    public function getFillable()
    {
        return $this->fillable;
    }
}
