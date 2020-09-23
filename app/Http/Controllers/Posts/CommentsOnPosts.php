<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Validator;
use App\Models\CommentOnPost;


class CommentsOnPosts extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']); 
    }
    /**
     * Display a listing of comments on a apost .
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
        $comment = CommentOnPost::where ("post_id",$pid)->orderBy('updated_at','desc')->get();
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
        $restrictions=[  
            'content' => 'required|min:2|max:200',

        ];
        $validator= Validator::make($request->all(),$restrictions);
        if($validator->fails()){
            echo "This comment doesn't match our restrictions";
            echo "required min of characters:2 and max:200";
            return response()->json($validator->errors(),400);
        }

        $comment=CommentOnPosts::create($request->all());
        $comment->user_id= $request->user()->id;
        $comment->post_id= $pid;
        $comment-> save();

        $postOwner=User::where('id',$post->user_id)->first();
        if($postOwner){
        $postOwner->notify(new NewCommentOnPost($postOwner));}
       return response()->json($comment,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show ($pid, $commentid)
    {
       
        $post=CommunityPost::find($pid);
        if(is_null($post)){
            return response()->json(["message"=>"This post is not found!"],404);
        }
        $comment = CommentOnPost::find($commentid);
        if(is_null($comment) || !($comment->post_id == $pid && $comment->id==$commentid)){
            return response()->json(["message"=>"This comment is not found!"],404);
        }
        return response()->json($post, 200);
    }
    

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit()
    {
        //
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

   }


}
