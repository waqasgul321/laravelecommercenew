<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->utype === 'ADM') {
                return $next($request); // Allow access to the next middleware/request
            } else {
                Session::flush(); // Clear all session data
                return redirect()->route('login'); // Redirect to login
            }
        }

        return redirect()->route('login'); // Redirect unauthenticated users
    }
}
