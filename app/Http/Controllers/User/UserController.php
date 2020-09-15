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

    //////////////////////////////////////////////////
    public function getFavorites(Request $request, $user_id)
    {
        // Authentication required
        $user = User::find($user_id);
        $favorites_ids = $user->only('favorites');
        $schools = School::all();
        $favorites = $schools->find($favorites_ids);
        return response()->json($schools,200);
                   
    }
}
