<?php

namespace App\Http\Controllers\Posts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Validator;
use App\Models\CommentOnPost;
use App\Models\User;
use App\Models\CommunityPost;
use App\Notifications\CommentsOnPostOwner;
use App\Models\School;

class CommentsOnPosts extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']); 
    }
    /**
     * Display a listing of comments on a apost .
     *
     * @return \Illuminate\Http\Response
     */

    public function index($pid)
    {
       
        $post=CommunityPost::find($pid);
        if(is_null($post)){
            return response()->json(["message"=>"This post is not found!"],404);
        }
        $comment = CommentOnPost::where ("post_id",$pid)->orderBy('updated_at','desc')->get();
        return response()->json($comment, 200);
    }

   
    /**
     * Store a new comment.
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
        $restrictions=[  
            'content' => 'required|min:2|max:200',

        ];
        $validator= Validator::make($request->all(),$restrictions);
        if($validator->fails()){
            echo "This comment doesn't match our restrictions";
            echo "required min of characters:2 and max:200";
            return response()->json($validator->errors(),400);
        }

        $comment=CommentOnPost::create($request->all());
        $comment->user_id= $request->user()->id;
        $comment->post_id= $pid;
        $comment-> save();

        $postOwner=User::where('id',$post->user_id)->first();
        if($postOwner){
        $postOwner->notify(new CommentsOnPostOwner($postOwner));}
       return response()->json($comment,201);
    }

  

    
 

     /**
     * Update the comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

   public function update(Request $request,$pid,$commentid)
   {
    $post=CommunityPost::find($pid);
    if(is_null($post)){
        return response()->json(["message"=>"This post is not found!"],404);
    }
    $comment=CommentOnPost::find($commentid);
    if(is_null($comment) || !($comment->post_id == $pid && $comment->id==$commentid)){
        return response()->json(["message"=>"This comment is not found!"],404);
    }
    if ($request->user()->id !== $comment->user_id){ 
        return response()->json(["message"=>"You cannot update thi comment"],401);
     }

     $restrictions=[
        'CommunityPost_Content' => 'sometimes|min:2|max:200'
    ];
    $validator= Validator::make($request->all(),$restrictions);
    if($validator->fails()){
        echo "This post can't be stored it doesn't match our restrictions";
        echo "required min of characters:2 and max:200";
        return response()->json($validator->errors(),400);
    }
    $comment->content=$request->content;
    $comment->save();                            
    return response()->json($post,200);

   }
   
    /**
     * Remove the comment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

   public function destroy(Request $request,$pid,$commentid)
   {
    $post=CommunityPost::find($pid);
    if(is_null($post)){
        return response()->json(["message"=>"This post is not found!"],404);
    }
    $comment=CommentOnPost::find($commentid);
    
    if(is_null($comment) || !($comment->post_id == $pid && $comment->id==$commentid)){
        return response()->json(["message"=>"This comment is not found!"],404);
      }
      if ($request->user()->id !== $comment->user_id){
        return response()->json(["message"=>"you cannot delete this comment!"],401);
    }
    $comment->delete();
    return response()->json(null,204);

   }


}
