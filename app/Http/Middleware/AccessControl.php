<?php

namespace App\Http\Middleware;

use Cookie;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;

class AccessControl
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
    public function handle($request, Closure $next, ...$scopes)
    {
        $cookie = Cookie::get('bearer');
        
        $request->headers->add(['Authorization' => "Bearer $cookie" ]);
        
        if ($request->user('api')->role_id == 3) {
            return $next($request);
        }
        return redirect('/');
    }
}
