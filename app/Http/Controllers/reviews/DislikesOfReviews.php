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
        $this->middleware('auth:api');
    }


    
      /**
     * Add or remove dislike and count numebr of dislikes.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function Dislikes(Request $request,$school_id,$review_id)
    {
     $school=School::find($school_id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }
    $review=Review::find($review_id);
        if(is_null($review)){
            return response()->json(["message"=>"This review is not found!"],404);
        }
            $likes=LikesOfReview::where('user_id',$request->user()->id)->first();
            $dislikes=DislikesOfReview::where('user_id',$request->user()->id)->first();
          


             if ( is_null ($dislikes))
            {
       $dislikes=DislikesOfReviews::create($request->all());
       $dislikes->user_id= $request->user()->id;
       $dislikes->review_id= $id;
       $review->num_of_dislikes++;
       $dislikes->save();

       if(!(is_null($likes)))
       {
       if($request->user()->id!==$likes->user_id)
      { return response()->json(["message"=>"You arenot the like owner!"],404);}

         $likes->delete();
       
       $review->num_of_likes--;
       }
       return response()->json($dislikes,201);
       }


       if ((!(is_null($dislikes)) && is_null($likes))
        {
         if($request->user()->id!==$dislikes->user_id)
      { return response()->json(["message"=>"You arenot the dislike owner!"],404);}

         $dislikes->delete();
        return response()->json(null,204);
       
       
        }
      


}
