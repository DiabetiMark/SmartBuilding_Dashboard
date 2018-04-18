<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    public $timestamps = true;
    
    protected $fillable = [
        'fieldName',
    ];

    public function getFillable()
    {
        return $this->$fillable;
    }
}
