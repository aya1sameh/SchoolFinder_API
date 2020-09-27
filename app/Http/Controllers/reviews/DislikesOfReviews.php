<?php

namespace App\Http\Controllers\reviews;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\DislikesOfReview;
use App\Models\LikesOfReview;

class DislikesOfReviews extends Controller
{ public function __construct()
    {
        $this->middleware('auth:api')->except(['numOfDislikes']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function numOfDislikes($id)
    {
        $review=review::find($id);
        if(is_null($review)){
            return response()->json(["message"=>"This review is not found!"],404);

         $dislikes = DislikesOfReview::where("review_id",$id)->count();
           return response()->json($dislikes,200);
    }

    
      /**
     * Add  dislike.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function AddOrRemoveDislikes(Request $request,$id,$id2,$user_id)
    {
    $review=review::find($id);
        if(is_null($review)){
            return response()->json(["message"=>"This review is not found!"],404);
            
            $likes=LikesOfReview::find($user_id);
            $dislikes=DislikesOfReview::find($user_id);

            if ( is_null ($likes) && is_null($dislikes))
            {
       $dislikes=DislikesOfReview::create($request->all());
       $dislikes->user_id= $request->user()->id;
       $dislikes->review_id= $id;
       $dislikes->save();
       return response()->json($dislikes,201);
       }

	
}


/**
     *  remove dislike.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
public function removeDislikes(Request $request,$id,$user_id,$id2)
    {

    $review=review::find($id);
    if(is_null($review)){
            return response()->json(["message"=>"This review is not found!"],404);
            
            $likes=LikesOfReview::find($user_id);

               if(DislikesOfReview::where('user_id', $user_id )->exists() && is_null($likes))
               {
             $dislikes=DislikesOfReview::find($id2);
            $dislikes->delete();
        return response()->json(null,204);
        }

            }

}
