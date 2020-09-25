<?php

namespace App\Http\Middleware;

use Closure;

class AuthKey
{
    /**
     * Handle an incoming request if it has the correct APP_KEY or not
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $app_id = 'fbdjhjxchkcvjxjcjvbhxjc';
        $app_secret = 'vasdhhasdhjadskdsfamcnhdsuhduhcsj';
        $key = $app_id.':'.$app_secret;
        $key_base64 = base64_encode($key);
        $final_key = base64_encode('school_finder_app_key').$key_base64;
        //echo $final_key;
        if($request->header('APP_KEY') != $final_key){
            return response()->json(['error'=>'App Key is not correct'],401);
        }
        return $next($request);
    }
}
