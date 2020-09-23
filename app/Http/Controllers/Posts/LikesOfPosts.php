<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LikesOfPosts extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']); 
    }
    /**
     * Display a listing of likes on a post.
     *
     * @return \Illuminate\Http\Response
     */
  
    public function index($id,$pid)
    {
        $school=School::find($id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }
        $post=CommunityPost::find($pid);
        if(is_null($post)){
            return response()->json(["message"=>"This post is not found!"],404);
        }
        $like = LikeOnPost::where ("post_id",$pid)->orderBy('updated_at','desc')->get();
        return response()->json($comment, 200);
    }

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
  
    public function store (Request $request,$pid)
    {
        $post=CommunityPost::find($pid);
        if(is_null($post)){
            return response()->json(["message"=>"This post is not found!"],404);
        }
       

        $like=LikeOnPost::create($request->all());
        $like->user_id= $request->user()->id;
        $like->post_id= $pid;
        $like-> save();
        $postOwner=User::where('id',$post->user_id)->first();

        if($postOwner){
        $postOwner->notify(new NewLikeOnPost($postOwner));}
       return response()->json($like,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show ($pid, $likeid)
    {
       
        $post=CommunityPost::find($pid);
        if(is_null($post)){
            return response()->json(["message"=>"This post is not found!"],404);
        }
        $like = CommentOnPost::find($likeid);
        if(is_null($like) || !($like->post_id == $pid && $like->id==$likeid)){
            return response()->json(["message"=>"This like is not found!"],404);
        }
        return response()->json($post, 200);
    }
    

    

    public function edit()
    {
        //
    }

    public function update()
   {
       //
   }

   public function destroy()
   {
       //
   }
}
