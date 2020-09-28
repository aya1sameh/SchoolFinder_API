<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\School;
use App\Http\Resources\Models\School as schoolResource;

class AppAdminController extends Controller
{
    /**
     * Return a list of paginated schools suggestions
     *
     * @return \Illuminate\Http\Response
     */
    public function getNewSchoolSuggestions()
    {  
        $schoolList= SchoolResource::collection(School::where("is_approved",false)->paginate(10));
        return response()->json($schoolList,200);
    }

    /**
     * Approves the suggestion of the school by changing the school's is_approved attribute
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function approveSuggestion($id)
    {
        $school=School::findOrFail($id);
        $is_approved=$school->is_approved;
        if($is_approved==1)
            return response()->json(["message"=>"No suggestion found"],404);

        $school->update(['is_approved'=>true]);
        return response()->json(new SchoolResource($school), 200);
    }
}
