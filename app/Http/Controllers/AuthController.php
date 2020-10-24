<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\User as UserResource;
use Validator;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Notifications\RegisterMailActivate;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;

class AuthController extends Controller
{
    /**Create Token Function 
     * 
     * Helps in creating the access token for each user.
     * 
     */
    private function createToken($user){
        //creating access tokens..
        $tokenResult = $user->createToken('school finder app');
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addDays(365);
        $token->save();
        $user->access_token = $tokenResult->accessToken;
        $user->save();
        return $tokenResult->accessToken;
    }

    /**
     * Login Request
     * @group  authentication system
     * 
     * used to login and create token of this specific user
     *  
     *
     * @bodyParam name either the email or the name
     * @bodyParam email either the email or the name
     * @bodyParam password required
     * @response 200{
     *  
     *      "access_token": "YOUR TOKEN HERE",
     *      "token_type": "bearer",
     * }
     * @response 400{
     *      "message" : "Not Registered"
     * }
     * @response 401{
     *      "message" : "Unauthorized"
     * }
     * 
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        $name = $request->name;
        //if user sent their email 
        if(filter_var($name, FILTER_VALIDATE_EMAIL)) 
            $user=User::where('email',$name)->first();
        //else if they sent their name instead 
        else $user=User::where('name',$name)->first();
        if(!$user) return response()->json(["message" => 'Register First Please :DU'], 400);

        //if user sent their email 
        if(filter_var($name, FILTER_VALIDATE_EMAIL)) 
            $user=Auth::attempt(['email' => $name, 'password' => $request->password]);
        //else if they sent their name instead 
        else $user=Auth::attempt(['name' => $name, 'password' => $request->password]);
        
        if(!$user) return response()->json(["message" => 'Wrong Name/Email or Password!!'], 402);
        
        $user = $request->user();
        if($user->email_verified_at == null) return response()->json(["message" => 'Check You mail please for Verification :D'], 401);
        $accessToken=$this->createToken($user);
        
        return response()->json([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
        ]);
    }
 
    /**
     * Register Request
     * @group  authentication system
     * 
     * used to register a user and if admin will create the access token else will send email verification
     *  
     *
     * @bodyParam name required 
     * @bodyParam email required Unique email for each user
     * @bodyParam password required Minimun 8 char
     * @bodyParam password_confirmation required Must be a the same as the password 
     * @bodyParam role {school_admin or school_finder}
     * @bodyParam phone_no {string -> "+201x-xxx-xxxx" Format}
     * @bodyParam address {string -> "Street - City" Format}
     * @bodyParam avatar {image}
     * 
     * @response 201{
     *      "message": "Successfully created user, just verify it!",
     *      "user": User object
     * }
     * if app_admin
     * @response 201{ 
     *      "message": "Successfully created user!",
     *      "access_token": "YOUR TOKEN HERE",
     *      "token_type": "Bearer",
     *      "user": User object
     * }
     * @response 401{
     *      "message" : 'A Specific Error will be displayed here acc. to what is missing'
     * }
     * 
     */
    public function register(Request $request) 
    { 
        //for validation 
        $rules = [
            'email' => 'required|string|email|unique:users', 
            'password' => 'required|string|min:8|confirmed', 
            'name' =>'required|string|min:3|max:64|unique:users',
            'role' =>'sometimes|string',
            'avatar' =>'image',
            // 'phone_no' =>'string',
            // 'address' =>'string',
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) 
            return response()->json(["message"=>$validator->errors()], 400); //bad request    
        
        $input = $request->all(); 
        $input['password'] = Hash::make($input['password']); 

        //create the user in the database 
        $user = User::create($input);

        if($request->hasFile('avatar'))
        {
            $Image=$request->file('avatar');
            $ImageName='user_images'.$user->id.'.'.$Image->getClientOriginalExtension();
            $path=$request->file('avatar')->move(public_path('/imgs/users_avatars'),$ImageName);
            $PhotoUrl='/imgs/users_avatars/'.$ImageName;
            $user->avatar= $PhotoUrl;
        }
        $user->save();

        //if the user is app admin so no need for verification
        if($user->role == 'app_admin' || $user->role == 'admin'){
            $user->email_verified_at = Carbon::now();
            $user->save();
            $accessToken=$this->createToken($user);
            $response = [
                'message' => 'Successfully created user!',
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
                'user'=>new UserResource($user),
            ];
            return response()->json(
                $response
                ,201
            );
        }
        else{ //else send email verification message
            $user->notify(new RegisterMailActivate($user));
            return response()->json(['message' => 'Successfully created your account, just verify it at your email !','user'=>new UserResource($user),], 201);
        } 
    }

    /**
     * Verify the Account by sending an email
     * @group  authentication system
     * 
     * It will be redirected to the "Login Page" after the verification complete.
     */
    public function registerActivate($token)
    {
        $user = User::where('remember_token', $token)->first();
        if (!$user) {
            return response()->json([
                'message' => 'This verification token is invalid..'
            ], 404);
        }
        $user->email_verified_at = Carbon::now();
        $user->save();
        $url = 'http://192.168.1.15:8081/';
        return redirect($url);
    }

     /**
     * Logout Request (Revoke the token) 
     * @group  authentication system
     * 
     * @response 200{
     *      "message":"Successfully logged out"
     * } 
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
