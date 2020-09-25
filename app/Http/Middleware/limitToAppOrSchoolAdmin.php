<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\School;

class limitToAppOrSchoolAdmin
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
        $school=School::findOrFail($request->id);

        if($role == 'school_finder_client')
            return response()->json(['message'=>'Unauthorized'],401);

        
        else if($role=='school_admin' && !($school->checkIfUserisAdmin($user->id)))
            return response()->json(["message"=>"You are not authorized to edit info of this school"],401);

        return $next($request);
    }
}
