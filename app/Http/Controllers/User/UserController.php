<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use Validator;

class UserController extends Controller
{
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
            return response()->json(["message"=>"Response not Found!!"],404);
        }
        return response()->json($user,200);
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
            return response()->json(["message"=>"Response not Found!!"],404);
        }
        $user->update($request->all());
        $user->save();
        return response()->json($user,200);
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
<<<<<<< HEAD

   
    
    
=======
>>>>>>> 87f73d9f46b5efa4c7565c7de994efa1b0dfe4dd
}
