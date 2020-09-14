<?php

namespace App\Http\Controllers\School;

use App\Http\Resources\Models\School as SchoolResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\School;
use Illuminate\Support\Facades\DB;
use Validator;


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
        $rules=[
            'name'=>'required|min:3',
            'iso'=>'required|min:2|max:2',
        ];

        $validator= Validator::make($request->all(), $rules);
        if($validator->fails())
            return response()->json($validator->errors(),400);
        
        $country= CountryModel::create($request->all());
        return response()->json($country,201);
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
