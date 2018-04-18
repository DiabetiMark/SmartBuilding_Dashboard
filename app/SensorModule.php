<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SensorModule extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'moduleName',
    ];

    public function rooms()
    {
        return $this->belongsToMany('App\Room');
    }

    public function dataRegisters()
    {
        return $this->belongsToMany('App\DataRegister');
    }
    
    public function getFillable()
    {
        return $this->$fillable;
    }
}
