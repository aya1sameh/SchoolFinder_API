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

        if($request->header('APP_KEY') != $final_key ){
            $input = $request->all();
            if($input != null ){
                $app_key = $input['APP_KEY']??null;
                if($app_key == $final_key){
                    return $next($request);
                }
            }
            return response()->json(['error'=>'App Key is not correct'],401);
        }
        return $next($request);
    }
}
