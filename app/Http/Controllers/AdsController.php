<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ads;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class AdsController extends Controller
{
    private $adsImagesDirectory="\ad_image";

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index','show']); 
    }
    /**
     * Display a listing of Ads, 5 per page in descending order 
     * according to the creation time.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //retrieve the ads by the latest creation
        $ads = Ads::orderBy('created_at', 'desc')->paginate(5);
        return response()->json($ads,200);
    }

    /**
     * Store a newly created ad in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user();
        $restrictions=[  
            'ad_content' => 'required|min:2|max:100',
            'ad_image_url'=> 'sometimes|image'
        ];
        $validator= Validator::make($request->all(),$restrictions);
        if($validator->fails()){
            echo "This ad can't be stored it doesn't match our restrictions";
            echo "required min of characters:2 and max:100";
            return response()->json($validator->errors(),400);
        }
        $ad = Ads::create($request->all());
        $ad->user_id = $user->id;
        $ad->save();
        $PhotoUrl = null;
        if($request->hasFile('ad_image_url'))
        {
            $Image=$request->file('ad_image_url');
            $ImageName='ad_image'.$ad->id.'.'.$Image->getClientOriginalExtension();
            $path=$request->file('ad_image_url')->move(public_path('/ad_image'),$ImageName);
            $PhotoUrl=url('/ad_image'.'/'.$ImageName);
            $ad->ad_image_url= $ImageName;
        }
        $ad->save();
        return response()->json($ad,201)->file($PhotoUrl);
    }

    /**
     * Display the specified ad by it's id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ad = Ads::find($id);
        if(!$ad) return response()->json(['error'=>'Response not Found'],404);
        return response()->json($ad,200);
    }

    /**
     * Update the specified ad in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $ad = Ads::find($id);
        $restrictions=[  
            'ad_content' => 'min:2|max:100',
            'ad_image_url'=> 'image'
        ];
        $validator= Validator::make($request->all(),$restrictions);
        if($validator->fails()){
            echo "This ad can't be stored it doesn't match our restrictions";
            echo "required min of characters:2 and max:100";
            return response()->json($validator->errors(),400);
        }

        if(!$ad) $ad = Ads::create($request->all());
        else {
            if($ad->ad_image_url !=null){
                $ad_image=$ad->ad_image_url;
                $imagepath=public_path().$this->adsImagesDirectory;
                $imagename='\ad_image'.$id.'.'.pathinfo($imagepath.$ad_image, PATHINFO_EXTENSION);
                File::delete($imagepath.$imagename);
            }
            $ad->update($request->all());
        }
        $ad->user_id = $user->id;
        if($request->hasFile('ad_image_url'))
        {
            $Image=$request->file('ad_image_url');
            $ImageName='ad_image'.$ad->id.'.'.$Image->getClientOriginalExtension();
            $path=$request->file('ad_image_url')->move(public_path('/ad_image'),$ImageName);
            $PhotoUrl=url('/ad_image'.$ImageName);
            $ad->ad_image_url= $ImageName;
        }
        $ad->save();
        return response()->json($ad,200);
    }

    /**
     * Remove the specified ad from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ad = Ads::find($id);
        if(!$ad) return response()->json(null,204);
        if($ad->ad_image_url !=null){
            $ad_image=$ad->ad_image_url;
            $imagepath=public_path().$this->adsImagesDirectory;
            $imagename='\ad_image'.$id.'.'.pathinfo($imagepath.$ad_image, PATHINFO_EXTENSION);
            File::delete($imagepath.$imagename);
        }
        $ad->delete();
        return response()->json(null,204);
    }
}
