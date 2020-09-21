<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CommunityPost;
use App\Models\School;
use Validator;
use Illuminate\Support\Facades\File;

class CommunityPostsController extends Controller
{   
    public function __construct()
    {
        //$this->middleware('auth')->except(['index','show']); /////////////////////////////////uncommented when we finish testing////////////////////////////////////////////////
    }
    private $PostImagesDirectory='/CommunityPostsImages';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {    
        $school=School::find($id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }
        $post = CommunityPost::where("school_id",$id)->paginate(10);
           return response()->json($post, 200);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
    public function store(Request $request,$id)
    {  //$school=School::find($id);
        //if(is_null($school)){
          //  return response()->json(["message"=>"This school is not found!"],404);
        //}
        
        $restrictions=[  
            'CommunityPost_Content' => 'required|min:2|max:400',
            'CommunityPostImages[]'=> 'sometimes|image'
        ];
        $validator= Validator::make($request->all(),$restrictions);
        if($validator->fails()){
            echo "This post can't be stored it doesn't match our restrictions";
            echo "required min of characters:2 and max:400";
            return response()->json($validator->errors(),400);
        }
        $post=CommunityPost::create($request->all());
        //$post->user_id= $request->user()->id;//////////////////////////not tested//////////////////////////////////////////////////////
        $post->school_id= $id;
        
        if($request->hasFile('CommunityPostImages'))
        {$i=1;
            $Images=$request->file('CommunityPostImages');
            foreach($Images as $Image){
                
                $ImageName='CommunityPostImage_withID_'.$post->id.'_'.$i.'.'.$Image->getClientOriginalExtension();
                $path=$Image->move(public_path('/CommunityPostsImages'),$ImageName);
                $PhotoUrl=url('/CommunityPostsImages'.$ImageName);
                //$post->CommunityPostImages[$i]=$ImageName;
                $i++;
            }
        }
        

        $post->save();
       return response()->json($post,201);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id,$id2)
    { 
        $school=School::find($id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }
        $post = CommunityPost::find($id2);
        if(is_null($post) || !($post->school_id == $id && $post->id==$id2)){
          return response()->json(["message"=>"This Post is not found!"],404);
        }
        return response()->json($post, 200);
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
        $post=CommunityPost::find($id2);
        if(is_null($post) || !($post->school_id == $id && $post->id==$id2)){
          return response()->json(["message"=>"This Post is not found!"],404);
        }
        
        /*if ($request->user()->id !== $post->user_id){ /////////////////////////////////uncommented when we finish testing////////////////////////////////////////////////
           return response()->json(["message"=>"sorry you are not the Post owner to update it :D"],401);
        }*/
        $restrictions=[
            'CommunityPost_Content' => 'required|min:2|max:400',
            'CommunityPostImages[]'=> 'sometimes|image',
        ];
        $validator= Validator::make($request->all(),$restrictions);
        if($validator->fails()){
            echo "This post can't be stored it doesn't match our restrictions";
            echo "required min of characters:2 and max:400";
            return response()->json($validator->errors(),400);
        }
        if($request->hasFile('CommunityPostImages'))
        {$i=0;
            $Images=$request->file('CommunityPostImages');
            foreach($Images as $Image){
                
                $ImageName='CommunityPostImage_withID_'.$post->id.'_'.$i.'.'.$Image->getClientOriginalExtension();
                $path=$Image->move(public_path('/CommunityPostsImages'),$ImageName);
                $PhotoUrl=url('/CommunityPostsImages'.$ImageName);
                //$post->CommunityPostImages[$i]=$ImageName;
                $i++;
            }
        }
        $post->save();                        /////////////will see which one is better later///////////////////////////////////////
        //$post->update($request->all());     ///////////////////
        return response()->json($post,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id,$id2)
    {   //$school=School::find($id);
        //if(is_null($school)){
          //  return response()->json(["message"=>"This school is not found!"],404);
        //}
        $post=CommunityPost::find($id2);
        if(is_null($post) || !($post->school_id == $id && $post->id==$id2)){
          return response()->json(["message"=>"This Post is not found!"],404);
        }
        /*if ($request->user()->id !== $post->user_id){/////////////////////////////////////uncommented when we finish testing///////////////////////////////////////
            return response()->json(["message"=>"sorry you are not the Post owner to delete it :D"],401);
        }*/
        if(is_null($post->CommunityPostImages)){
            $post->delete();
            return response()->json(null,204);
        }
        $imageName=public_path('/CommunityPostImages').$post->CommunityPostImages;
        File::delete($imageName);
        //$post->delete();
        return response()->json(null,204);
        
        
    }
      
        
    
    
}
