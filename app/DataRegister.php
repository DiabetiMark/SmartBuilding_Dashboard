<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataRegister extends Model
{
    public $timestamps = true;
    public $table = 'dataRegisters';

    protected $fillable = [
        'field_id',
        'sensor_id',
        'value',
    ];

    public function getFillable()
    {
        return $this->fillable;
    }

    public function sensor()
    {
        return $this->belongsTo('App\Sensor', 'sensor_id', 'id');
    }

    public function field(){
        return $this->belongsTo('App\Field', 'field_id', 'id');
    }
}
