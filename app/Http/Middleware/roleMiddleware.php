<?php

namespace App\Http\Middleware;

use Closure;

class roleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role_name)
    {
        /*if( auth()->check() && !auth()->user()->haveRole($role_name)){
            return redirect('anotherPage');
        }*/

        return $next($request);
    }
}
