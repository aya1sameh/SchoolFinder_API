<?php

namespace App\Http\Middleware;

use Closure;

class SchoolAdminMiddleware
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
        $user = $request->user();
        $role = $user->role;
        if($role == 'school_admin')
            return $next($request);
        else return response()->json(['message'=>'Unauthorized'],401);
    }
}
