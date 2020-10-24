<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CommunityPost;
use App\Models\School;
use Validator;
use Illuminate\Support\Facades\File;
use App\Notifications\NewCommunityPostInASchool;
use App\Models\User;


class CommunityPostsController extends Controller
{   private $PostImagesDirectory="\imgs\CommunityPostsImages";
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
    {    
        $school=School::find($id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }
        $posts = CommunityPost::where("school_id",$id)->orderBy('updated_at','desc')->paginate(10);
        $users= array();
        foreach($posts as $post)
        {   $users =array_merge($users,[User::where('id',$post->user_id)->first()]);
            $users=array_filter($users);
        }
       return response()->json(array('Posts' => $posts,'Users'=> $users));   
        
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
    {  $school=School::find($id);
        if(is_null($school)){ 
            return response()->json(["message"=>"This school is not found!"],404);
        }
        
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
        $post->user_id= $request->user()->id; 
        $post->school_id= $id;
        
        if($request->hasFile('CommunityPostImages'))
        {$i=1;
            $Images=$request->file('CommunityPostImages');
            foreach($Images as $Image){
                
                $ImageName='CommunityPostImage_withID_'.$post->id.'_'.$i.'.'.$Image->getClientOriginalExtension();
                $path=$Image->move(public_path('/imgs/CommunityPostsImages'),$ImageName);
                $PhotoUrl=url('/imgs/CommunityPostsImages'.$ImageName);
                $post->CommunityPostImages=array_merge($post->CommunityPostImages,['/imgs/CommunityPostsImages/'.$ImageName]);
                $post->CommunityPostImages=array_filter($post->CommunityPostImages);
                $i++;
            }
        }
        

        $post->save();
        $SchoolAdmin=User::where('id',$school->admin_id)->first();
        if($SchoolAdmin){
        $SchoolAdmin->notify(new NewCommunityPostInASchool($SchoolAdmin));}
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
    public function update(Request $request,$id,$id2)
    {   $school=School::find($id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }
        $post=CommunityPost::find($id2);
        if(is_null($post) || !($post->school_id == $id && $post->id==$id2)){
          return response()->json(["message"=>"This Post is not found!"],404);
        }
        
        if ($request->user()->id !== $post->user_id){ 
           return response()->json(["message"=>"sorry you are not the Post owner to update it :D"],401);
        }
        $restrictions=[
            'CommunityPost_Content' => 'sometimes|min:2|max:400',
            'CommunityPostImages[]'=> 'sometimes|image',
        ];
        $validator= Validator::make($request->all(),$restrictions);
        if($validator->fails()){
            echo "This post can't be stored it doesn't match our restrictions";
            echo "required min of characters:2 and max:400";
            return response()->json($validator->errors(),400);
        }
        $Images=$post->CommunityPostImages;
        if($post->CommunityPostImages){
            
            $imagepath=public_path().$this->PostImagesDirectory;
            for($i=1;$i<=count($Images);$i++){ 
                $imagename='\CommunityPostImage_withID_'.$id2.'_'.$i.'.'.pathinfo(public_path().$Images[$i-1], PATHINFO_EXTENSION);
                File::delete($imagepath.$imagename);
            }
        }
        $post->CommunityPostImages=array();
        if($request->hasFile('CommunityPostImages'))
        {$i=1;
            $Images=$request->file('CommunityPostImages');
            foreach($Images as $Image){
                
                $ImageName='CommunityPostImage_withID_'.$post->id.'_'.$i.'.'.$Image->getClientOriginalExtension();
                $path=$Image->move(public_path('/imgs/CommunityPostsImages'),$ImageName);
                $PhotoUrl=url('/imgs/CommunityPostsImages'.$ImageName);
                $post->CommunityPostImages=array_merge($post->CommunityPostImages,['/imgs/CommunityPostsImages/'.$ImageName]);
                $post->CommunityPostImages=array_filter($post->CommunityPostImages);
                $i++;
            }
        }
        $post->CommunityPost_Content=$request->CommunityPost_Content;
        $post->save();                            
        return response()->json($post,200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id,$id2)
    {   
        $school=School::find($id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }
        $post=CommunityPost::find($id2);
        
        if(is_null($post) || !($post->school_id == $id && $post->id==$id2)){
          return response()->json(["message"=>"This Post is not found!"],404);
        }
        if ($request->user()->id !== $post->user_id){
            return response()->json(["message"=>"sorry you are not the Post owner to delete it :D"],401);
        }
        if(is_null($post->CommunityPostImages)){
            $post->delete();
            return response()->json(null,204);
        }
    
        $Images=$post->CommunityPostImages;
        $imagepath=public_path().$this->PostImagesDirectory;
        

        for($i=1;$i<=count($Images);$i++){ 
        $imagename='\CommunityPostImage_withID_'.$id2.'_'.$i.'.'.pathinfo(public_path().$Images[$i-1], PATHINFO_EXTENSION);
        File::delete($imagepath.$imagename);
        }
        $post->delete();
        return response()->json(null,204);
        
        
    }
    public function ShowPostsByUserID(Request $request,$id)
    { $school=School::find($id);
        if(is_null($school)){
            return response()->json(["message"=>"This school is not found!"],404);
        }
        $userid=$request->user()->id;
        $post=CommunityPost::where('user_id',$userid)->orderBy('updated_at','desc')->paginate(10);
        return response()->json($post, 200);
    } 
        
    
  


      }

