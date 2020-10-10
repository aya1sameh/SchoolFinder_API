<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\User as UserResource;
use App\Models\School;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    private $userImagesDirectory="/imgs/users_avatars";
    
     /**
     * Display a listing of Users, 10 per page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(10);
        return response()->json($users,200);
    }

    /**
     * Display the specified user by it's id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if(is_null($user)){
            return response()->json(["message"=>"Response not Found!!"],404);
        }
        return response()->json($user,200);
    }

    /**
     * Display the specified user by using the authentication access token.
     *
     * First this is a GET request which gets the current logged in user and return it.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $user = $request->user();
        if(is_null($user)){
            return response()->json(["message"=>"User not Found!!"],404);
        }
        return response()->json(new UserResource($user),200);
    }

    /**
     * Update the specified user info in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();
        if(is_null($user)){
            return response()->json(["message"=>"User not Found!!"],404);
        }
        $input = $request->all();
        $name = $input['name']??null;
        if($name){
            $userName=User::where('name',$name)->first();
            if($userName){
                return response()->json(["message"=>"Username is already taken!!"],404);
            }
        }
        $removeAvatar = $input['remove_avatar']??false;
        if($removeAvatar){
            $id = $user->id;
            //delete the avatar..
            if($user->avatar !=null){
                $user_image=$user->avatar;
                $imagepath=public_path().$this->userImagesDirectory;
                $imagename='/user_images'.$id.'.'.pathinfo($imagepath.$user_image, PATHINFO_EXTENSION);
                File::delete($imagepath.$imagename);
                $user->avatar=null;
                $user->save();
            }
        }
        //$user->save();
        if($request->hasFile('avatar'))
        {
            $id = $user->id;
            //delete the avatar first..
            if($user->avatar !=null){
                $user_image=$user->avatar;
                $imagepath=public_path().$user_image;
                File::delete($imagepath);
            }
            $user->update($input);
            //then update with the new avatar..
            $Image=$request->file('avatar');
            $ImageName='user_images'.$user->id.'.'.$Image->getClientOriginalExtension();
            $path=$request->file('avatar')->move(public_path('/imgs/users_avatars'),$ImageName);
            $PhotoUrl='/imgs/users_avatars/'.$ImageName;
            $user->avatar= $PhotoUrl;
            $user->save();
        }
        else{
            $user->update($input);
        }
        $user->save();
        return response()->json(new UserResource($user),200);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        if(is_null($user)){
            return response()->json(["message"=>"Response not Found!!"],404);
        }
        $id = $user->id;
        if($user->avatar !=null){
            $user_image=$user->avatar;
            $imagepath=public_path().$user_image;
            File::delete($imagepath);
        }
        $user->delete();
        return response()->json(null,204);
    }
    

    public function getFavorites(Request $request)
    {
        // Authentication required
        $user = $request->user();
        if (is_null($user)){
            return response()->json(["message"=>"Unauthorized"],401);
        }
        $favorites_ids = $user->favorites;
        $favorites = School::find($favorites_ids);
        return response()->json($favorites,200);              
    }

    public function AddFavorites(Request $request, $school_id)
    {
        
        $school = School::find($school_id);
        if(is_null($school)){
            return response()->json(["message"=>"Not Found"],404);
        }

        $user = $request->user();
        if (is_null($user)){
            return response()->json(["message"=>"Unauthorized"],401);
        }

        $favorites = $user->favorites;

        if (empty($favorites)){
            $length = 0;
        }
        else{

            if(in_array ( $school_id, $favorites , False)){
                return response()->json(["message"=>"Dupication error"],403);
            } 
            $length = count($favorites);   
        }
       
        $favorites[(int)$length] = (int)$school_id;
        $user->favorites = $favorites;
        $user->save();
        return response()->json($favorites,200);              
    }

    public function RemoveFavorites(Request $request, $school_id)
    {
        
        $school = School::find($school_id);
        if(is_null($school)){
            return response()->json(["message"=>"Response not Found!!"],404);
        }

        $user = $request->user();
        if (is_null($user)){
            return response()->json(["message"=>"Unauthorized"],401);
        }
        $favorites = $user->favorites;

        if (empty($favorites)){
            return response()->json(["message"=>"Response not Found!!"],404);
        }
        else{

            if(in_array ( $school_id, $favorites , False)){
                $index = array_search( $school_id,$favorites,true); 
                unset($favorites[$index]);                    //Delete the item
                $favorites = array_values($favorites);        //Re-indexing the array
                $user->favorites = $favorites;
                $user->save();
                return response()->json($favorites,200); 
            } 
            else
                return response()->json(["message"=>"Response not Found!!"],404);  
        }
                      
    }

   
    
    
}
