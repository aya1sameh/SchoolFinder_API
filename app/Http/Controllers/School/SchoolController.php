<?php

namespace App\Http\Controllers\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        //TODO:: except is_approved law normal user!
        $schoolList= SchoolResource::collection(School::paginate(20));
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

        /*Creating new school object*/
        $school= School::create($request->except('certificates','stages'));

        /*Creating certificates,stages objects*/
        $id=$school->id;
        $this->storeSchoolCertificates($request,$id);
        $this->storeSchoolStages($request,$id);
        
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
        $imageName=$school->name."/".strval($numberOfImages+1);
        
        $image=SchoolImage::create([
            "school_id"=>$school->id,
            "url"=>$imageName
        ]);
        
        $request->image->move(public_path('/imgs/schools'),$imageName);
        return response()->json(new SchoolImageResource($image),201);
    }

    /**
     * Display the specified school.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(new SchoolResource(School::findOrFail($id)),200);
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
        
        $school->update($request->all());
        //TODO:: except('is_') law school admin 
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteSchoolFacility(Request $request,$id)
    {
        /*Making sure that school exists*/
        $school=School::findOrFail($id);

        $rules=["type"=>"required"];
        $validator= Validator::make($request->all(), $rules);

        if(SchoolFacility::where('school_id',$id)->where('type',$request->type)->delete())
            return response(null,204);

        else
            return response()->json(["message"=>"Facility not found"],404);
            
    }

}
