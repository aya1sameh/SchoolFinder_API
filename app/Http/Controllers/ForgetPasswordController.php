<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ForgetPasswordController extends Controller
{
    /**
     * Forget Password Request 
     * @group  authentication system
     * 
     * This will send a reset link to the email of the user
     * 
     * @response 200{
     *      "message" => 'Reset password link sent on your email id.'
     * } 
     */
    public function forget() {
        $credentials = request()->validate(['email' => 'required|email']);
        $request=request();
        $app_id = 'fbdjhjxchkcvjxjcjvbhxjc';
        $app_secret = 'vasdhhasdhjadskdsfamcnhdsuhduhcsj';
        $key = $app_id.':'.$app_secret;
        $key_base64 = base64_encode($key);
        $final_key = base64_encode('school_finder_app_key').$key_base64;
        $request->headers->set('APP_KEY', $final_key);
        Password::sendResetLink($credentials);

        return response()->json(["message" => 'Reset password link sent on your email id.']);
    }

    /**
     * Reset Password Request 
     * @group  authentication system
     * 
     * This will open a new View asking you to put the new password. 
     * 
     * @response 200{
     *      "message" => "Password has been successfully changed"
     * } 
     */
    public function reset() {

        $credentials = request()->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|confirmed'
        ]);

        $reset_password_status = Password::reset($credentials, function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
        });

        if ($reset_password_status == Password::INVALID_TOKEN) {
            return response()->json(["error" => "Invalid token provided"], 400);
        }

        return response()->json(["message" => "Password has been successfully changed"]);
    }
}
