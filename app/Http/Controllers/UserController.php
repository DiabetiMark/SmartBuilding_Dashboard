<?php

namespace App\Http\Controllers;

use App\User;
use Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    public function login(Request $request)
    {
        $rules = [
            'username' => 'required|max:255',
            'password' => 'required|max:255',
        ];

        $customMessages = [
            'email.required' => trans('errors.emailRequired'),
            'email.max' => trans('errors.emailMax'),
            'password.required' => trans('errors.passwordRequired'),
            'password.min' => trans('errors.passwordMax'),
        ];

        $this->validate($request, $rules, $customMessages);

        if (Auth::attempt(['username' => request('username'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp', [$user->role])->accessToken;

            Cookie::make('bearer', $success['token'], 86400);

            return response()->json(['success' => $success], 200);
        } else {
            $errors['errors']["password"][0] = trans('errors.inlogfail');

            return response()->json($errors, 401);
        }
    }

    public function logout()
    {
        $id = Auth::user()->id;
        DB::table('oauth_access_tokens')
            ->where('user_id', $id)
            ->delete();
        Cookie::queue(\Cookie::forget('bearer'));
        return response()->json(['succes' => 'true']);
    }

    public function getAuthUser()
    {
        $customer_user = Auth::user();
        return response()->json($customer_user);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|unique:users|max:45',
            'email' => 'required|unique:users|email|max:45',
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

    //custom actions
    public function getRooms($id)
    {
        return $rooms = User::find($id)->rooms;
    }
}
