<?php


namespace App\Http\Controllers\reviews;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\School;
use Validator;
class ReviewsController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth:api')->except(['index','show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {   $school=School::find($id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }
        $reviews = Review::where("school_id",$id)->orderBy('updated_at','desc')->paginate(10);
        return response()->json($reviews,200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {   $school=School::find($id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }
        $restrictions=[
            'review_description' => 'required|min:2|max:400',
            'rating'=> 'required|min:1|max:10',
        ];
        $validator= Validator::make($request->all(),$restrictions);
        if($validator->fails()){
            echo "This Review can't be stored it doesn't match our restrictions";
            echo "Content required min of characters:2 and max:400";
            echo "rating is required min:1 and max:10";
            return response()->json($validator->errors(),400);
        }
        
       $review=Review::create($request->all());
       $review->user_id= $request->user()->id;
       $review->school_id= $id;
       $review->save();
       $school->calculateOverAllRating();
       $school->changeRatedBy('+');
       return response()->json($review,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,$id2)
    {   $school=School::find($id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }
        $review = Review::find($id2);
        if(is_null($review) || !($review->school_id == $id && $review->id==$id2)){

            return  response()->json(["message"=>"Review not Found!!"],404);
        }
        return response()->json($review,200);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,$id2)
    {   $school=School::find($id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }

        $review=Review::find($id2);
        
        if(is_null($review) || !($review->school_id == $id && $review->id==$id2)){
          return response()->json(["message"=>"This review is not found!"],404);
        }
        if ($request->user()->id !== $review->user_id){/////////////////////////////////////////////////////////////////////////////////////
            return response()->json(["message"=>"sorry you are not the review owner to update it :D"],401);
        }
        $restrictions=[
            'review_description' => 'required|min:2|max:400',
            'rating'=> 'required|min:1|max:10',
        ];
        $validator= Validator::make($request->all(),$restrictions);
        if($validator->fails()){
            echo "This Review can't be stored it doesn't match our restrictions";
            echo "specify the new rating and description";
            return response()->json($validator->errors(),400);
        }
        $review->update($request->all());
        return response()->json($review,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id,$id2)
    {$school=School::find($id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }
        $review=Review::find($id2);
        if(is_null($review) || !($review->school_id == $id && $review->id==$id2)){
          return response()->json(["message"=>"This review is not found!"],404);
        }
        if ($request->user()->id !== $review->user_id){/////////////////////////////////////////////////////////////////////////////////////
            return response()->json(["message"=>"sorry you are not the review owner to delete it :D"],401);
        }
        $review->delete();
        $school->calculateOverAllRating();
        $school->changeRatedBy('-');
        return response()->json(null,204);
    }
}
