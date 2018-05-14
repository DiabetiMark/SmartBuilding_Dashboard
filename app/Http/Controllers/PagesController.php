<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Redirect;

class PagesController extends Controller
{
    public function resetPassword($user_id, $email, $hash)
    {
        $count = DB::table('password_resets')
        ->where('email', $email)
        ->where('hash', $hash)
        ->where('user_id', $user_id)
        ->where('created_at', '>=', Carbon::now()->subMinutes(20))->count();
        if($count){
            return view('pages/login.passwordReset', ['user_id' => $user_id, 'email' => $email, 'hash' => $hash]);
        }
        return redirect('/login');
    }
}
