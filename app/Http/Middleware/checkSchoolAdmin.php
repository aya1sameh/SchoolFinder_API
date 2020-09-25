<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\School;

/*Middleware that checks that passed school exists 
and if authorized user is school admin checks if he is an admin for the passed school*/
class checkSchoolAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request,Closure $next)
    {
        $school=School::findOrFail($request->id);
        
        /*A school admin is unauthrized to add facility to any other school but his*/
        $user=$request->user();
        if(!$school->checkIfUserisAdmin($user->id))
            return response()->json(["message"=>"You are not authorized to add facility to this school"],401);

        return $next($request);
    }
}
