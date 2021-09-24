<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $response = $next($request);
            return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','Fri, 01 Jan 1990 00:00:00 GMT');
        }
        session()->flash('msg', 'Please sign in with your credentials and access your account, thankyou.');
        session()->flash('msg_class', 'alert alert-danger');
        session()->flash('msg_title', 'Unauthorized Access!');
        if ($guard == 'admin') {
            return redirect()->route('administrator.auth.signin');    
        }
        return redirect()->route('client.auth.signin');
    }
}
