<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataRegister extends Model
{
    public $timestamps = true;
    public $table = 'dataRegisters';

    protected $fillable = [
        'field_id',
        'sensorModule_id',
        'value',
    ];

    public function getFillable()
    {
        return $this->fillable;
    }

    public function sensorModules()
    {
        return $this->belongsTo('App\SensorModule', 'sensorModule_id', 'id');
    }
}
