<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Http\Controllers\Auth\VerificationController;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Notifications\RegisterMailActivate;

class AuthController extends Controller
{
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
     * @bodyParam name 
     * @bodyParam email required either the email or the name
     * @bodyParam password required
     * @response 200{
     *  
     *      "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiZTg1M2U3MjNmMjBlNDg3MDBlNDhkYTU2ZmU2MDQ3MGU3ZGFmNjM2YTRmNmM3NTAyYWY3NGM3YTQzYzQyZWM2NmY0NDEzYTY2MTczMTdlZWIiLCJpYXQiOjE1OTk1MDAyMzgsIm5iZiI6MTU5OTUwMDIzOCwiZXhwIjoxNjMxMDM2MjM4LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.Fo0udtMETBRLa4hYX99uErc7eOxTkPAFvaUffpogHnBo2xAMAwRyq-u15L2Hx510kQS2RqlHhOdzuSvIbtPIYJ6OyjlbP9XQxBSbVEKo3Pcbr9twTrAmwPifpEgc3zT9q_NRrnm9UabzfMy3-5tCwvGdNAv3yZet4CjVqTF-7lmFIt2MjSH1Si2WxlGa8Y3DMzvr0t4PuA8_ju8MK5Ql8ylNF10DQyi2YbbULXVHNJXKYIqDRElsAhzN185GTxYHvudvH_VIPOHMCkUeR4i5FAPHkhB_PGSrF9nde6CfbAQ7GIkiC5q9-wB4_Dt5sYjAX1y0VqUiL-y0V99XKS88_1AWkue2W1YfsxI76hcmTIGUR_57IxWVJPNlGXPzpUGdsHlKBmyH7mIHmo8wVMIq3woEy2ilfCLqyVAMIca-94nqY7iqmjhlrE_rBgvfpRz19n2AOWgI9Q33SrNYR4MM_g9XONXpYsjbpAz5BzahWbLRALTqGQNgKy7GNJbMld6Q0jKrZqek0T7Tb6sP1jSgWQaLz5VBhUJvZRDW2zO6-acBg3yQvRTqyMVeFigZaG4Rx9CnH-xd40WeeEjhA--uyCj0XD2zfhdPxNLhYvFa3tCYCJJwuffogpkAcd0pwuUsPS1Rvw75z5AqObFWiYqmwWDbwyrpF_xsVOUWIrqHxX0",
     *      "token_type": "bearer",
     * }
     * @response 400{
     *      "error" : "Not Registered"
     * }
     * @response 401{
     *      "error" : "Unauthorized"
     * }
     * 
     */
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'string',
            'email' => 'string',
            'password' => 'required|string',
        ]);
        
        if($request->name != null){
            $name = $request->name;
            $user=User::where('name',$name)->first();
        }
        else if($request->email != null){
            $email = $request->email;
            $name =$email;
            $user=User::where('email',$email)->first();
        }
        else 
            return response()->json(['error' => 'Either name or email must be written'], 400);

        if(!$user) return response()->json(['error' => 'Not Registered'], 400);

        //if user sent their email 
        if(filter_var($name, FILTER_VALIDATE_EMAIL)) 
            $user=Auth::attempt(['email' => $name, 'password' => $request->password]);
        //else if they sent their name instead 
        else $user=Auth::attempt(['name' => $name, 'password' => $request->password]);
        
        if(!$user) return response()->json(['error' => 'Unauthorized'], 401);
        
        $user = $request->user();
        if($user->email_verified_at == null) return response()->json(['error' => 'Unverified'], 401);
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
     * @bodyParam role {app_admin or school_admin or school_finder}
     * @response 200{
     *      "message": "Successfully created user, just verify it!"
     * }
     * if app_admin
     * @response 200{ 
     *      "message": "Successfully created user!",
     *      "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYzZiMDk1YWVlMmI4ZWViNjBlMWIxYjFmZDkzMTBlNTJlZDgxZjZlMGY5ZGVlOTc0ZWVjMzYyOWZhNDFjMjQyY2YzYTFlNjIyYTQwNzkwMjQiLCJpYXQiOjE2MDA3ODAyODQsIm5iZiI6MTYwMDc4MDI4NCwiZXhwIjoxNjMyMzE2Mjg0LCJzdWIiOiIyMDYiLCJzY29wZXMiOltdfQ.njxPCFpUOJQYLS8DHnyFtZV-HdfagNM9BNTKReD2M0vCy6RJ2v-j1cL1EFSe0V-Rc4Jf_OteNngTyfNijdXVgetZ2uhL5pIvrZE2MX14BUe3f9UBlhq5inkt5vVLoKj1M6IKjubO0djBS1h0j_m0TjCqaJcIxv04QJx_PQKefabb1Ta6dStbiPuu7jE9SiecA3eyfzhCJdxsQv-XaB9w2RJ05sqBaZ-Mou5whklOXXQTakmnxOx98xaiTqCHXrrq9VzI0vMRv63kOHOCCslO0gig6bx4ztlLImF15Fw_w69Zn7eh0MWsotziDsKqjOuR1mOVsBopc74ejPzzDQeUV2hbmUABEuEBQWSCqlDwxfEHJ59K-dOoMERYTEqjZF4--DIgb6ML2n3MiVnMIA1iV4VV8eUyaPfndXoBdvzA5C83e0jpVas8k8oRC03NObcSK_PBdAegBNFkMcb3GWwhnjNQidB8QsmrZBeVzEMneYd_p_4psxOQEcMVChUAYunmg_-f_sJicZmLQ9_gez3hSG5VHPp_1waocTxGwiohVNtSeYWUkwDDl5nbf61AtIILg_ZJnEa00e7Ys15TnGunqYHOG9aEkhUtERxevwVN4U-okBs2ok5jYSvi2_JpjNkMgSM9kInkLgqxsQy0npgbWXGJUjkPCOniPGMKTfWOhJE",
     *      "token_type": "Bearer"
     * }
     * @response 401{
     *      "error" : 'A Specific Error will be displayed here acc. to what is missing'
     * }
     * 
     */
    public function register(Request $request) 
    { 
        //for validation 
        $rules = [
            'email' => 'required|string|email|unique:users', 
            'password' => 'required|string|min:8|confirmed', 
            'name' =>'required|string|unique:users',
            'role' =>'string',
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) 
            return response()->json(['error'=>$validator->errors()], 400); //bad request    
        
        $input = $request->all(); 
        $input['password'] = Hash::make($input['password']); 

        //create the user in the database 
        $user = User::create($input);
        //if the user is app admin so no need for verification
        if($user->role == 'app_admin' || $user->role == 'admin'){
            $user->email_verified_at = Carbon::now();
            $user->save();
            $accessToken=$this->createToken($user);
            return response()->json([
                'message' => 'Successfully created user!',
                'access_token' => $accessToken,
                'token_type' => 'Bearer',
            ]);
        }
        else{ //else send email verification message
            $user->notify(new RegisterMailActivate($user));
            return response()->json(['message' => 'Successfully created user, just verify it!'], 201);
        } 
    }

    /**
     * Verify the Account by sending an email
     * @group  authentication system
     * 
     * A "Thanks" View will be shown after the verification complete.
     */
    public function registerActivate($token)
    {
        $user = User::where('verify_token', $token)->first();
        if (!$user) {
            return response()->json([
                'message' => 'This verification token is invalid..'
            ], 404);
        }
        $user->email_verified_at = Carbon::now();
        $user->save();
        return view('thanks');
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
