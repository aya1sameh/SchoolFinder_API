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
        $this->middleware('auth')->except(['index']); 
    }
    /**
     * Display a listing of likes on a post.
     *
     * @return \Illuminate\Http\Response
     */
  
    public function numOfLikes($pid)
    {
       
        $post=CommunityPost::find($pid);
        if(is_null($post)){
            return response()->json(["message"=>"This post is not found!"],404);
        }
        $like = LikeOnPost::where ("post_id",$pid)->count();
        return response()->json($like, 200);
    }

  


    /**
     * Add like on post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
  
    public function addLike (Request $request,$pid,$user_id)
    {
        $post=CommunityPost::find($pid);
        if(is_null($post)){
            return response()->json(["message"=>"This post is not found!"],404);
        }
         $like=LikeOnPost::find($user_id);
         if(is_null($like))
         {
       
        $like=LikeOnPost::create($request->all());
        $like->user_id= $request->user()->id;
        $like->post_id= $pid;
        $like-> save();

        $postOwner=User::where('id',$post->user_id)->first();
        if($postOwner){
        $postOwner->notify(new NewLikeOnPost($postOwner));}
       return response()->json($like,201);
       }
    }

   

    

    /**
     * remove like on post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   public function removeLike(Request $request,$pid,$like_id)
   {
        $post=CommunityPost::find($pid);
        if(is_null($post)){
            return response()->json(["message"=>"This post is not found!"],404);
        }

        $like=LikeOnPost::find($like_id);
             $like->delete();
        return response()->json(null,204);
   }
}
