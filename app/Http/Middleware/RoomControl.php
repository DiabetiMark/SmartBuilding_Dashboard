<?php

namespace App\Http\Middleware;

use Cookie;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;

class RoomControl
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;

    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $cookie = Cookie::get('bearer');
        
        $request->headers->add(['Authorization' => "Bearer $cookie" ]);

        foreach($request->user('api')->rooms as $room)
        {
            if(intval($room->id) == intval($request->id)){
                
                return $next($request);
            }
        }
        return redirect('/');
    }
}
