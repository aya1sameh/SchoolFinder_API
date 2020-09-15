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

use App\Http\Resources\Models\School as SchoolResource;
use App\Http\Resources\Models\SchoolFacility as ModelsSchoolFacility;
use App\Http\Resources\Models\SchoolStage as SchoolStageResource;


class SchoolController extends Controller
{
    /**
     * Display a listing of schools (including schools that are not aproved yet) to the App admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $schoolList= SchoolResource::collection(School::paginate(20));
        return response()->json($schoolList,200);
    }


    /**
     * Display a listing of schools to the school finder user.
     *
     * @return \Illuminate\Http\Response
     */
    public function getApprovedSchool()
    {
        //DB::table('schools')->where('is_approved',true)->
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
        $rules=[
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

        $validator= Validator::make($request->all(), $rules);
        if($validator->fails())
            return response()->json($validator->errors(),400);

        /*Creating new school object*/
        $school= School::create($request->except('certificates','stages'));

        $id=$school->id;
        /*Creating certificates,stages objects*/
        for($index=0;$index<count($request->certificates);$index++)
        {
            SchoolCertificate::create([
                'certificate'=>$request->certificates[$index],
                'school_id'=>$id
            ]);
        }
        
        for($index=0;$index<count($request->stages);$index++)
        {
            SchoolStage::create([
                'statge'=>$request->stages[$index],
                'school_id'=>$id
            ]);
        }
        
        return response()->json(new SchoolResource($school),201);
    }


    /**
     * Add facilities to School
     *
     * @param  \Illuminate\Http\Request  $request
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
