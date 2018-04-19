<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique|max:45',
            'password' => 'required',
            'name' => 'required|max:45',
            'phone' => 'required|max:30',
            'isAdmin' => 'required|numeric|max:1',
        ]);

        $item = new User;

        if ($this->setCreate($item, $request)) {
            return;
        }

        $error = [
            "status" => xxxx,
            "message" => "User niet aangemaakt",
        ];

        return response()->json($error, 404);
    }

    public function showAll()
    {
        return $users = User::all();
    }

    public function showOne($id)
    {
        $user = User::select('username', 'name', 'phone', 'isAdmin')
        ->find($id);

        if($user !== null) 
        {
            return response()->json($user);
        }

        $error = [
            "status" => xxxx,
            "message" => 'User kon niet gevonden worden',
        ];

        return response()->json($error, 404);
    }

    public function update($id, Request $reqeust)
    {
        $this->validate($request, [
            'username' => 'unique|max:45',
            'name' => 'max:45',
            'phone' => 'max:30',
            'isAdmin' => 'numeric|max:1',
        ]);

        $item = User::find($id);

        if($item !== null){
            if($this->setUpdate($item, $request)){
                return;
            }

            $error = [
                "status" => xxxx,
                "message" => 'Het wijzigen van de user is niet gelukt',
            ];
            $errorCode = 405;
        } else {

            //if the category cannot be found
            $error = [
                "status" => xxxx,
                "message" => 'User kon niet gevonden worden',
            ];
            $errorCode = 404;
        }

        //return the response of the error in json
        return response()->json($error, $errorCode);
        
    }
}
