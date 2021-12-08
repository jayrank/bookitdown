<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class FUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('fuser')->check()) {
            return $next($request);
        }
        return redirect()->route('flogin');
    }
}
