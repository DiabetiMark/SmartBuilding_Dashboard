<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Redirect;

class PagesController extends Controller
{
    public function resetPassword($user_id, $email, $hash)
    {
        return view('pages.passwordReset', ['user_id' => $user_id, 'email' => $email, 'hash' => $hash]);
    }
}
