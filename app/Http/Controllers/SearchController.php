<?php

namespace App\Http\Controllers\Search;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\User;
use Validator;

/*Used Models and Resources*/
use App\Models\School;

class SearchController extends Controller
{
    public function searchSchool(Request $request)
    {
        $name = $request->get('name');
        $school_info = School::where('name', 'like', "%{$name}%")
                        ->orderBy('rating','desc')
                        ->paginate(10);

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
            $Filtered = DB::table('schools')
                        ->where('fees', '<', (int)$maxfees)
                        ->orderBy('rating','desc')
                        ->paginate(10);
            if(count($Filtered) < 1)
                return response()->json(["message"=>"No school found"],404);
            foreach($Filtered as $filtaraya){
                array_push($FilteredIDS,$filtaraya->id); 
            } 
       }
        
       if(!is_null($language)){
            if(!$FilteredIDS){
                $Filtered = DB::table('schools')
                            ->where('language', (string)$language)
                            ->orderBy('rating','desc')
                            ->paginate(10);
            }else{
                $Filtered = DB::table('schools')
                                ->where('language', (string)$language)
                                ->whereIn('id', $FilteredIDS)
                                ->orderBy('rating','desc')
                                ->paginate(10);  
                }
            if(count($Filtered) < 1)
                return response()->json(["message"=>"No school found"],404);
            $FilteredIDS=array();
            foreach($Filtered as $filtaraya){
                array_push($FilteredIDS,$filtaraya->id);} 
        }

        if(!is_null($address)){
            if(!$FilteredIDS){
                 $Filtered = DB::table('schools')
                             ->where('address', 'like', '%' . $address . '%')
                             ->orderBy('rating','desc')
                             ->paginate(10);
             }else{
             $Filtered = DB::table('schools')
                             ->where('address', 'like', '%' . $address . '%')
                             ->whereIn('id', $FilteredIDS)
                             ->orderBy('rating','desc')
                             ->paginate(10);
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
            $Filtered = DB::table('schools')
                ->where('id', $FilteredIDS)
                ->orderBy('rating','desc')
                ->paginate(10); 
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
            $Filtered = DB::table('schools')
                ->where('id', $FilteredIDS)
                ->orderBy('rating','desc')
                ->paginate(10);  
         } 
        return Response()->json($Filtered, 200);
    }
}
