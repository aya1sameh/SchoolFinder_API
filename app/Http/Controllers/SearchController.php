<?php

namespace App\Http\Controllers\Search;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Validator;

/*Used Models and Resources*/
use App\Models\User;
use App\Http\Resources\Models\School as SchoolResource;
use App\Models\School;

class SearchController extends Controller
{
    public function searchSchool(Request $request)
    {
        $name = $request->get('name');
        $school_info = SchoolResource::collection(School::where('name', 'like', "%{$name}%")
                        ->orderBy('rating','desc')
                        ->paginate(10));
       

        return response()->json($school_info,200);
    }

    public function filter(Request $request)
    {
        $maxfees = $request->MaxFees;
        $language = $request->Language;
        $address = $request->Address;
        $certificate = $request->Certificate;
        $stage = $request->Stage;
        $FilteredIDS = array();

        if(!is_null($maxfees)) {
            $Filtered = School::
                        where('fees', '<', (int)$maxfees)
                        ->orderBy('rating','desc')
                        ->get();
            if(count($Filtered) < 1)
                return response()->json(["message"=>"No school found"],404);
            foreach($Filtered as $filtaraya){
                array_push($FilteredIDS,$filtaraya->id); 
            } 
       }
        
       if(!is_null($language)){
            if(!$FilteredIDS){
                $Filtered = School::
                            where('language', (string)$language)
                            ->orderBy('rating','desc')
                            ->get();
            }else{
                $Filtered = School::
                                where('language', (string)$language)
                                ->whereIn('id', $FilteredIDS)
                                ->orderBy('rating','desc')
                                ->get();  
                }
            if(count($Filtered) < 1)
                return response()->json(["message"=>"No school found"],404);
            $FilteredIDS=array();
            foreach($Filtered as $filtaraya){
                array_push($FilteredIDS,$filtaraya->id);} 
        }

        if(!is_null($address)){
            if(!$FilteredIDS){
                 $Filtered = School::
                             where('address', 'like', '%' . $address . '%')
                             ->orderBy('rating','desc')
                             ->get();
             }else{
             $Filtered = School::
                             where('address', 'like', '%' . $address . '%')
                             ->whereIn('id', $FilteredIDS)
                             ->orderBy('rating','desc')
                             ->get();
             } 
            if(count($Filtered) < 1)
                return response()->json(["message"=>"No school found"],404);
            $FilteredIDS=array();
            foreach($Filtered as $filtaraya){
                array_push($FilteredIDS,$filtaraya->id);} 
        }  
        if(!is_null($certificate)){
            if(!$FilteredIDS){
                 $Filtered = DB::table('school_certificates')
                             ->where('certificate', $certificate )
                             ->get();
             }else{
                 $Filtered = DB::table('school_certificates')
                             ->where('certificate', $certificate )
                             ->whereIn('school_id', $FilteredIDS)
                             ->get();
             } 
             if(count($Filtered) < 1)
                return response()->json(["message"=>"No school found"],404);
            $FilteredIDS=array();
            foreach($Filtered as $filtaraya){
                array_push($FilteredIDS,$filtaraya->school_id);} 
            $Filtered = School::
                where('id', $FilteredIDS)
                ->orderBy('rating','desc')
                ->get();
         } 
         if(!is_null($stage)){
            if(!$FilteredIDS){
                 $Filtered = DB::table('school_stages')
                             ->where('stage', $stage )
                             ->get();
             }else{
                 $Filtered = DB::table('school_stages')
                             ->where('stage', $stage )
                             ->whereIn('school_id', $FilteredIDS)
                             ->get();
                 
             }
             if(count($Filtered) < 1)
                return response()->json(["message"=>"No school found"],404);
            $FilteredIDS=array();
            foreach($Filtered as $filtaraya){
                array_push($FilteredIDS,$filtaraya->school_id);}  
            $Filtered = School::
                where('id', $FilteredIDS)
                ->orderBy('rating','desc')
                ->get();  
         } 
        return Response()->json(SchoolResource::collection($Filtered), 200);
    }
}
