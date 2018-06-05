<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public $timestamps = true;
    public $table = 'roles';
    protected $fillable = [
        'role',
    ];

    public function getFillable()
    {
        return $this->fillable;
    }

    public function getRole()
    {
        return $this->hasMany('App\User');
    }
}
