<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CommunityPost;
use Validator;

class CommunityPostsController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']); ////////////////////////////////////////////////////////////////////////////////////
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $post = CommunityPost::paginate(10);
        return response()->json($post, 200);
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
        $restrictions=[
            
            "id"=> 'required',//////////////////////////////////////////ask////////////////////////////////////////////////////
            "user_id"=> 'required',
            "school_id"=> 'required',
            'CommunityPost_Content' => 'required|min:2|max:400',
            "created_at"=> 'required',
        ];
        $validator= Validator::make($request->all(),$restrictions);
        if($validator->fails()){
            echo "This post can't be stored it doesn't match our restrictions";
            echo "required min of characters:2 and max:400";
            return response()->json($validator->errors(),400);
        }
        
       $post=CommunityPost::create($request->all());
       return response()->json($post,201);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $post=CommunityPost::find($id);
        if(is_null($post)){
          return response()->json(["message"=>"This Post is not found!"],404);
        }
        return response()->json($post,201);
         
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
        $post=CommunityPost::find($id);
        if(is_null($post)){
          return response()->json(["message"=>"This Post is not found!"],404);
        }
        
        if ($request->user()->id !== $post->user_id){ ////////////////////////////////////////////////////////////////////////////////////
           return response()->json(["message"=>"sorry you are not the Post owner to update it :D"],401);
        }
        $restrictions=[
            
            'CommunityPost_Content' => 'required|min:2|max:400',
            "updated_at"=> 'required',
        ];
        $validator= Validator::make($request->all(),$restrictions);
        if($validator->fails()){
            echo "This post can't be stored it doesn't match our restrictions";
            echo "required min of characters:2 and max:400";
            echo "required updated at date and time";
            return response()->json($validator->errors(),400);
        }
        $post->update($request->all());
        return response()->json($post,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post=CommunityPost::find($id);
        if(is_null($post)){
          return response()->json(["message"=>"This Post is not found!"],404);
        }
        if (auth()->user()->id !== $post->user_id){////////////////////////////////////////////////////////////////////////////////////
            return response()->json(["message"=>"sorry you are not the Post owner to delete it :D"],401);
        }
        $post->delete();
        return response()->json(null,204);
    }
}
