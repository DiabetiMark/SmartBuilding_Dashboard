<?php

namespace App\Http\Controllers;

use Cookie;
use App\User;
use App\Role;
use Carbon\Carbon;
use App\Mail\passwordReset;
use App\Mail\newUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{

    public function login(Request $request)
    {
        $rules = [
            'username' => 'required|max:255',
            'password' => 'required|max:255',
        ];

        $customMessages = [
            'username.required' => "Emailadres is verplicht.",
            'username.max' => "Emailadres mag niet meer dan 255 tekens bevatten.",
            'password.required' => 'Wachtwoord is verplicht.',
            'password.max' => 'Wachtwoord mag niet meer dan 255 tekens bevatten.',
        ];

        $this->validate($request, $rules, $customMessages);
        if (Auth::attempt(['username' => request('username'), 'password' => request('password')])) {
            $user = Auth::user();
            $role = Role::find($user->role);
            $role = $role[0]->role;
            
            $success['token'] = $user->createToken('MyApp', [$role])->accessToken;

            Cookie::make('bearer', $success['token'], 86400);

            return response()->json(['success' => $success], 200);
        } else {
            $errors['errors']["password"][0] = "De combinatie van e-mailadres en wachtwoord is niet geldig.";

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
            'email' => 'required|unique:users|email|max:45',
            'name' => 'required|max:45',
            'username' => 'required|unique:users|max:45',
        ]);

        $item = new User;

        $hash = bin2hex(random_bytes(7));

        $request->password = $hash;
        
        if ($this->setCreate($item, $request)) {
            $data = array();

            if(!empty($request->rooms)){
                foreach($request->rooms as $room){
                    $dataRow =                 
                    array(
                        'user_id' => $item->id,
                        'room_id' => $room,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    );
                    array_push($data, $dataRow);
                }
    
                DB::table('room_user')->insert($data);
            }


            Mail::to($request->email)->send(new newUser($request->name, $hash, $request->username));
            return $this->showAll();
        }

        $error = [
            "status" => xxxx,
            "message" => "User niet aangemaakt",
        ];

        return response()->json($error, 404);
    }

    public function showAll()
    {
        $data = array(
            'users' => User::all(),
            'room_user' => app('App\Http\Controllers\RoomUserController')->showAll(),
        );

        return response()->json($data);
    }

    public function showOne($id)
    {
        $user = User::select('username', 'name', 'phone', 'email')
        ->find($id);

        if($user !== null) 
        {
            return response()->json($user);
        }

        $error = [
            "status" => 9999,
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

    public function resetPasswordemail(Request $request)
    {

        $rules = [
            'email' => 'required|email|max:255',
        ];

        $customMessages = [
            'email.required' => 'Emailadres is verplicht.',
            'email.email' => 'Emailadres moet een \'@\' en een \'.\' bevatten.',
            'email.max' => 'Emailadres kan niet meer dan 255 tekens bevatten.',
        ];

        $this->validate($request, $rules, $customMessages);

        $user = User::select('id', 'email', 'name')
            ->where('email', $request->email)
            ->first();

        if ($user !== null) {
            $hash = bin2hex(random_bytes(17));

            if ($forget_password = DB::table('password_resets')->insert(
                [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'hash' => $hash,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            )){
                Mail::to($user->email)->send(new passwordReset(env('APP_URL') . "/login/wachtwoord/" . $user->id . "/" . $user->email . "/" . $hash, $user->name));
            }
        }
    }

    public function resetPassword(Request $request)
    {
        $rules = [
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'password' => 'required|confirmed|min:1|max:255',
            'user_id' => 'required'
        ];

        $customMessages = [
            'password.required' => 'wachtwoord is verplicht.',
            'password.confirmed' => 'wachtwoord komt niet overeen.',
            'password.min' => 'wachtwoord mag niet minder dan 1 teken bevatten.',
            'password.max' => 'wachtwoord mag niet meer dan 255 tekens bevatten.',
        ];

        $this->validate($request, $rules, $customMessages);

        if(DB::table('password_resets')
            ->where('email', $request->email)
            ->where('hash', $request->hash)
            ->where('user_id', $request->user_id)
            ->where('created_at', '>=', Carbon::now()->subMinutes(20))->count()){
            if(User::find($request->user_id)->update(['password' => $request->password])){
                DB::table('password_resets')
                ->where('email', $request->email)
                ->where('hash', $request->hash)
                ->where('user_id', $request->user_id)
                ->where('created_at', '>=', Carbon::now()->subHour())->delete();
            }
        } 
    }

    public function getAllValues($id)
    {
        $user = User::find($id);
        $user->role->role;
        unset($user->role_id);
        unset($user->created_at);
        unset($user->updated_at);
        $index = 0;
        foreach($user->rooms as $room){
            unset($room->pivot);
            $user->rooms[$index] = app('App\Http\Controllers\RoomController')->getAllValuesRoom($room->id);
            $index++;
        }
        return $user;
    }
    public function getAll()
    {
        $users = User::all();
        foreach($users as $user){
            $user->role->role;
            unset($user->role_id);
            unset($user->created_at);
            unset($user->updated_at);
            $index = 0;
            foreach($user->rooms as $room){
                unset($room->pivot);
                $user->rooms[$index] = app('App\Http\Controllers\RoomController')->getAllValuesRoom($room->id);
                $index++;
            }
        }
        return $users;
    }
}
