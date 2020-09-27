<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        if(is_null($review)){
            return response()->json(["message"=>"This review is not found!"],404);

         $likes = LikesOfReview::where("review_id",$id)->count();
           return response()->json($likes,200);
    }
}

  

    /**
     * Add like.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function IncrementLikes(Request $request,$id)
    {
    $review=review::find($id);
        if(is_null($review)){
            return response()->json(["message"=>"This review is not found!"],404);

            $likes=LikesOFReview::create($request->all());
       $likes->user_id= $request->user()->id;
          $likes->review_id= $id;
       $likes->save();
       return response()->json($likes,201);

        }
    }


    /**
     * Remove like.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function DecrementLikes(Request $request,$id1,$id2)
    {
        $review=review::find($id);
        if(is_null($review)){
            return response()->json(["message"=>"This review is not found!"],404);

            $likes=LikesOfReview::find($id2);
            $likes->delete();
        return response()->json(null,204);

    }
}
}
