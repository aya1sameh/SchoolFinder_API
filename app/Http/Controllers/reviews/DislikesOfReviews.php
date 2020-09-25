<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
     * Add dislike.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function IncrementLikes(Request $request,$id)
    {
    $review=review::find($id);
        if(is_null($review)){
            return response()->json(["message"=>"This review is not found!"],404);

            $dislikes=DislikesOFReview::create($request->all());
       $dislikes->user_id= $request->user()->id;
          $dislikes->review_id= $id;
       $dislikes->save();
       return response()->json($dislikes,201);

        
    }


    /**
     * Remove dislike.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function DecrementLikes(Request $request,$id1,$id2)
    {
        $review=review::find($id);
        if(is_null($review)){
            return response()->json(["message"=>"This review is not found!"],404);

            $dislikes=DislikesOfReview::find($id2);
            $dislikes->delete();
        return response()->json(null,204);

    }
}
