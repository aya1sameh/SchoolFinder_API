<?php

namespace App\Http\Middleware;

use Closure;

class SchoolFinderMiddleware
{
    /**
     * Handle an incoming request to check if the user is a school finder client or not.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        $role = $user->role;
        if($role == 'school_finder_client' || $role == 'school_finder')
            return $next($request);
        else return response()->json(['message'=>'Unauthorized'],401);
    }
}
