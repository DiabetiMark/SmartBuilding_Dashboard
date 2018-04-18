<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataRegister extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'value',
    ];

    public function getFillable()
    {
        return $this->$fillable;
    }
}
