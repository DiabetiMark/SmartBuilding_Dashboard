<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    public $timestamps = true;
    public $table = 'fields';
    protected $fillable = [
        'fieldName',
        'fieldType',
    ];

    public function getFillable()
    {
        return $this->fillable;
    }
}
