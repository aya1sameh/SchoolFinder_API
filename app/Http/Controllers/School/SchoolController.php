<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\User;
use Validator;

/*Used Models and Resources*/
use App\Models\School;
use App\Models\SchoolCertificate;
use App\Models\SchoolStage;
use App\Models\SchoolFacility;
use App\Models\SchoolImage;

use App\Http\Resources\Models\School as SchoolResource;
use  App\Http\Resources\Models\SchoolImage as SchoolImageResource;


class SchoolController extends Controller
{
    private $schoolStoringRules=[
        'name'=>'required|min:3|unique:schools',
        'gender'=>'required',
        'language'=>'required',
        'address'=>'required',
        'phone_number'=>'required|numeric|unique:schools',
        'fees'=>'required|numeric',
        'establishing_year'=>'required',
        'certificates'=>'present|array',
        'stages'=>'present|array',
    ];
    
    private $schoolImagesDirectory="/imgs/schools/";

    /*
    * class constructor that calls the suitable middleware to each route
    */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index','show']); 
        $this->middleware('admin')->only(['destroy']);
        $this->middleware('limitToAppOrSchoolAdmin')->only(['addSchoolFacility','uploadSchoolImage','update','deleteSchoolImage','deleteSchoolFacility']);
    }

    /**
     * Store a newly created school Stage in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    private function storeSchoolStages(Request $request,$id)
    {
        for($index=0;$index<count($request->stages);$index++)
        {
            SchoolStage::create([
                'stage'=>$request->stages[$index],
                'school_id'=>$id
            ]);
        }
    }

    /**
     * Store a newly created school certificate in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    private function storeSchoolCertificates(Request $request,$id)
    {
        for($index=0;$index<count($request->certificates);$index++)
        {
            SchoolCertificate::create([
                'certificate'=>$request->certificates[$index],
                'school_id'=>$id
            ]);
        }
    }

    
    /**
     * Display a listing of schools (including schools that are not aproved yet) to the App admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //TODO:: orderby: rating*ratedby
        $schoolList= SchoolResource::collection(School::where("is_approved",true)->orderBy('rating', 'desc')
                    ->orderBy('rated_by','desc')->paginate(10));
        return response()->json($schoolList,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /*Checking for required data*/
        $validator= Validator::make($request->all(), $this->schoolStoringRules);
        if($validator->fails())
            return response()->json($validator->errors(),400);

        /*Creating new school object and filtering params based on role for safety*/
        $user_role=$request->user()->role;
        if($user_role=='school_finder_client' || $user_role=="school_admin")
            $params=$request->except('certificates','stages','is_approved','admin_id','rated_by','rating');
        else
        {
            $params=$request->except('certificates','stages','rated_by','rating');
            $params=array_merge($params,["is_approved"=>1]);
        }
            

        $school= School::create($params);
        /*Add admin id if user is admin*/
        if($user_role=='school_admin')
        {
            $school->admin_id=$request->user()->id;
            $school->save();
        }

        /*Creating certificates,stages objects*/
        $id=$school->id;
        $this->storeSchoolCertificates($request,$id);
        $this->storeSchoolStages($request,$id);
        $school=School::find($id);  /*to update school info before returning it*/

        return response()->json(new SchoolResource($school),201);
    }

    /**
     * Add facilities to School
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $id 
     * @return \Illuminate\Http\Response
     */
    public function addSchoolFacility(Request $request,$id)
    {
        $school=School::findOrFail($id);

        $rules=[
            "type"=>"required",
            "number"=>"required"
        ];

        $validator= Validator::make($request->all(), $rules);
        if($validator->fails())
            return response()->json($validator->errors(),400);

            
        SchoolFacility::create(array_merge($request->all(),["school_id"=>$id]));
        return response()->json(new SchoolResource($school),201);
    }

    /**
     * Upload school image
     *
     * @param  \Illuminate\Http\Request  $request
     * @param $id 
     * @return \Illuminate\Http\Response
     */
    public function uploadSchoolImage(Request $request,$id)
    {
        request()->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
            
        $school=School::findOrFail($id);
        $numberOfImages=DB::table("school_images")->where('school_id',$school->id)->count();
        $extension = $request->file('image')->getClientOriginalExtension();
        $imageName=($school->name)."_".strval($numberOfImages+1).'_'.time().".".$extension;
        
        $image=SchoolImage::create([
            "school_id"=>$school->id,
            "url"=>$imageName
        ]);
        
        $request->image->move(public_path($this->schoolImagesDirectory),$imageName);
        return response()->json(new SchoolImageResource($image),201);
    }

    /**
     * Display the specified school.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
       
        /*checking if access token is sent to request*/
        $isAdmin=false;
        $access_token=$request->header('access_token')??null;
        if($access_token)
        {
            $user=User::where('access_token',$access_token)->first();
            if($user->role == "app_admin")
                $isAdmin=true;     
        }
        if($isAdmin)
            $school=School::findOrFail($id);
        else
            $school=School::where('is_approved',true)->findOrFail($id);
            
        return response()->json(new SchoolResource($school),200);
    }

    /**
     * Update the specified school in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $school=School::findOrFail($id);

        if($request->stages)
            $this->storeSchoolStages($request,$id);

        if($request->certificates)
            $this->storeSchoolCertificates($request,$id);
        
        if($request->user()->role=="school_admin")
            $school->update($request->except(['admin_id','is_approved']));
        else
            $school->update($request->all());
            
        return response()->json(new SchoolResource($school), 200);
    }

    /**
     * Remove the specified school from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $school=School::findOrFail($id);
        $school->delete();
        return response(null,204);
    }

    /**
     * Remove the specified school facility from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSchoolFacility(Request $request,$id)
    {
        $rules=["type"=>"required"];
        $validator= Validator::make($request->all(), $rules);

        if(SchoolFacility::where('school_id',$id)->where('type',$request->type)->delete())
            return response(null,204);

        else
            return response()->json(["message"=>"Facility not found"],404);
            
    }

    public function Filter(Request $request)
    {
        $maxfees = $request->MaxFees;
        $language = $request->Language;
        $address = $request->Address;
        $certificate = $request->Certificate;
        $stage = $request->Stage;
        $FilteredIDS = NULL;

        if(!is_null($maxfees)) {
            if(is_null($FilteredIDS)){
                $Filtered = DB::table('schools')
                            ->where('fees', '<', (int)$maxfees)
                            ->get();
                $FilteredIDS = array_column(json_decode($Filtered,true),'id');
            }else{
                $Filtered = DB::table('schools')
                            ->where('fees', '<', (int)$maxfees)
                            ->whereIn('id', $FilteredIDS)
                            ->get();
                $FilteredIDS = array_column(json_decode($Filtered,true),'id');
            }    
        }
        if(!is_null($language)){
            if(is_null($FilteredIDS)){
                $Filtered = DB::table('schools')
                            ->where('language', (string)$language)
                            ->get();
                $FilteredIDS = array_column(json_decode($Filtered,true),'id');
            }else{
            $Filtered = DB::table('schools')
                            ->where('language', (string)$language)
                            ->whereIn('id', $FilteredIDS)
                            ->get();    
                $FilteredIDS = array_column(json_decode($Filtered,true),'id');
            }   
        } 
        if(!is_null($address)){
            if(is_null($FilteredIDS)){
                $Filtered = DB::table('schools')
                            ->where('address', 'like', '%' . $address . '%')
                            ->get();
                $FilteredIDS = array_column(json_decode($Filtered,true),'id');
            }else{
            $Filtered = DB::table('schools')
                            ->where('address', 'like', '%' . $address . '%')
                            ->whereIn('id', $FilteredIDS)
                            ->get();
                $FilteredIDS = array_column(json_decode($Filtered,true),'id');
            }   
        } 
       
        if(!is_null($certificate)){
            if(is_null($FilteredIDS)){
                $Filtered = DB::table('school_certificates')
                            ->where('certificate', $certificate )
                            ->get();
                $FilteredIDS = array_column(json_decode($Filtered,true),'school_id');
                $Filtered = School::find($FilteredIDS);
            }else{
                $Filtered = DB::table('school_certificates')
                            ->where('certificate', $certificate )
                            ->whereIn('school_id', $FilteredIDS)
                            ->get();
                $FilteredIDS = array_column(json_decode($Filtered,true),'school_id');
                $Filtered = School::find($FilteredIDS);
            }   
        }
        
        if(!is_null($stage)){
            if(is_null($FilteredIDS)){
                $Filtered = DB::table('school_stages')
                            ->where('stage', $stage )
                            ->get();
                $FilteredIDS = array_column(json_decode($Filtered,true),'school_id');
                $Filtered = School::find($FilteredIDS);
            }else{
                $Filtered = DB::table('school_stages')
                            ->where('stage', $stage )
                            ->whereIn('school_id', $FilteredIDS)
                            ->get();
                $FilteredIDS = array_column(json_decode($Filtered,true),'school_id');
                $Filtered = School::find($FilteredIDS);
            }   
        }
        return Response()->json([
            'Schools' => $Filtered
        ], 200);
    }

    public function searchSchool(Request $request)
    {
        $name = $request->get('name');
        $school_info = School::where('name', 'like', "%{$name}%")
                         ->get();

        return Response()->json([
            'search_results' => $school_info
        ], 200);
    }
    /**
     * Remove the specified Image from storage and removes it from the public directory
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSchoolImage(Request $request,$id)
    {
        /*Making sure that school exists*/
        $rules=["url"=>"required"];
        $schoolImage=SchoolImage::where('url',$request->url)->firstOrFail();
        
        SchoolImage::where(['school_id'=>$id,'url'=>$request->url])->delete();
        $imageName=public_path().$this->schoolImagesDirectory.($request->url);
        File::delete($imageName);
        return response(null,204);
    }
}
