<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\School;
use Validator;

class UserController extends Controller
{
    public function test($id)
    {
        $user = User::find($id);
        if($user == null) return response()->json('no response found',404);
        return response()->json($user->test(),200);
    }

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(10);
        return response()->json($users,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //for validation 
        $rules = [
            'email' => 'required|string|email|unique:users', 
            'password' => 'required|string|min:8|confirmed', 
            'name' =>'required|string|unique:users',
        ];
        $validator = Validator::make($request->all(),$rules);
        if ($validator->fails()) 
            return response()->json(['error'=>$validator->errors()], 400); //bad request    
        
        $input = $request->all(); 
        $input['password'] = Hash::make($input['password']); 

        //create the user in the database and send email verification message
        $user = User::create($input); 
        return response()->json($user,201);
    }

    /**
     * Display the specified resource.
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if(is_null($user)){
            return response()->json(["message"=>"Response not Found!!"],404);
        }
        $user->update($request->all());
        return response()->json($user,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if(is_null($user)){
            return response()->json(["message"=>"Response not Found!!"],404);
        }
        $user->delete();
        return response()->json(null,204);
    }

    public function getFavorites(Request $request)
    {
        
        $user = $request->user();
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
