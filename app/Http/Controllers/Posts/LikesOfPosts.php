<?php

namespace App\Http\Controllers\Posts;
use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\CommunityPost;
use App\Models\LikeOnPost;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\CommentsOnPostOwner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class LikesOfPosts extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['ShowLikes']); 
    }  
   
     /**
     * view list of likes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function ShowLikes($school_id,$post_id)
     {
     $school=School::find($id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }
     $post=CommunityPost::where('post_id',$post_id)->get();
        if(is_null($post))
        {
            return response()->json(["message"=>"This post is not found!"],404);
        }

        $user_id=LikesOnPost::where('post_id',$post_id)->value('user_id');
        $users=User::where('id',$user_id);
        return response()->json( $users, 200);


     }

    /**
     * Add or remove like on post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  
    public function addOrRemoveLike (Request $request,$school_id,$post_id)
    {
     $school=School::find($school_id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }
        $post=CommunityPost::where('post_id',$post_id)->get();
        if(is_null($post)){
            return response()->json(["message"=>"This post is not found!"],404);
        }


         $like=LikesOnPost::where('user_id',$request->user()->id)->first();

         if(is_null($like))
         {
       
        $like=LikeOnPost::create($request->all());
        $like->user_id= $request->user()->id;
        $like->post_id= $pid;
        $post->num_of_likes++;
        $like-> save();
       
else
{
   if($request->user()->id!==$like->user_id)
      { return response()->json(["message"=>"You arenot the like owner!"],404);}
        $post->num_of_likes--;
    $like->delete();
        return response()->json(null,204);
        
}


}

       }
    

   

    

