<?php

namespace App\Http\Controllers\reviews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\LikesOfReview;
use App\Models\DislikesOfReview;

class LikesOfReviews extends Controller
{ public function __construct()
    {
        $this->middleware('auth:api')->except(['numOfLikes']);
    }
    /**
     * Display number of likes.
     *
     * @return \Illuminate\Http\Response
     */
    public function numOfLikes($id)
    {
        $review=review::find($id);
        if(is_null($review))
            return response()->json(["message"=>"This review is not found!"],404);

         $likes = LikesOfReview::where("review_id",$id)->count();
           return response()->json($likes,200);
    }

  

    /**
     * Add like.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addLikes(Request $request,$id,$user_id)
    {
    $review=review::find($id);
        if(is_null($review)){
            return response()->json(["message"=>"This review is not found!"],404);
        }
            $likes=LikesOfReview::find($user_id);
            $dislikes=DislikesOfReview::find($user_id);

            if ( is_null ($likes) && is_null($dislikes))
            {
       $likes=LikesOFReview::create($request->all());
       $likes->user_id= $request->user()->id;
       $likes->review_id= $id;
       $likes->save();
       return response()->json($likes,201);
       }
	
    }

    /**
     *remove like.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeLikes(Request $request,$id,$user_id,$id2)
    {

    $review=review::find($id);
    if(is_null($review)){
            return response()->json(["message"=>"This review is not found!"],404);

            $dislikes=DislikesOfReview::find($user_id);

            if(LikesOfReview::where('user_id', $user_id )->exists() && is_null($dislikes))
            {
            $likes=LikesOfReview::find($id2);
            $likes->delete();
        return response()->json(null,204);
}
             }
            }


        
    



}
