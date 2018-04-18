<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function setCreate($item, $request)
    {
        $fillables = $item->getFillable();

        //loops through every fillable variable
        foreach ($fillables as $fillable) {
            $item->$fillable = $request->$fillable;
        }
        if($item->save()){
            return $item;
        }
        return false;
    }

    protected function setUpdate($item, $request)
    {
        $fillables = $item->getFillable();
        //loops through every fillable variable
        foreach ($fillables as $fillable) {
            if (!empty(request($fillable))) {
                $item->update([$fillable => $request->$fillable]);
            }
        }
        return $item->save();
    }
}
