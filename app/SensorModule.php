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

    public function sensors()
    {
        return $this->hasMany('App\Sensor', 'sensorModule_id', 'id');
    }
    
    public function getFillable()
    {
        return $this->fillable;
    }
}
