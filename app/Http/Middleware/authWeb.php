<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as Auth;
use Cookie;
class authWeb
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;

    }

    public function handle($request, Closure $next)
    {
        $cookie = Cookie::get('bearer');
        
        $request->headers->add(['Authorization' => "Bearer $cookie" ]);
        
        if($this->auth->guard("api")->check()){

            $request->headers->remove('Authorization');

            return $next($request);
        }
        return redirect('login');
    }
}