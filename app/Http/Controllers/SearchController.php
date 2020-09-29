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

        return Response()->json([
            'search_results' => $school_info
        ], 200);
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
                            ->orderBy('rating','desc')
                            ->paginate(10);
                if(!is_null($FilteredIDS)){ $FilteredIDS = array_column(json_decode($Filtered,true),'id');}
            }else{
                $Filtered = DB::table('schools')
                            ->where('fees', '<', (int)$maxfees)
                            ->whereIn('id', $FilteredIDS)
                            ->orderBy('rating','desc')
                            ->paginate(10);
                if(!is_null($FilteredIDS)){ $FilteredIDS = array_column(json_decode($Filtered,true),'id');}
            }    
        }
        if(!is_null($language)){
            if(is_null($FilteredIDS)){
                $Filtered = DB::table('schools')
                            ->where('language', (string)$language)
                            ->orderBy('rating','desc')
                            ->paginate(10);
                if(!is_null($FilteredIDS)){ $FilteredIDS = array_column(json_decode($Filtered,true),'id');}
            }else{
            $Filtered = DB::table('schools')
                            ->where('language', (string)$language)
                            ->whereIn('id', $FilteredIDS)
                            ->orderBy('rating','desc')
                            ->paginate(10);    
                if(!is_null($FilteredIDS)){ $FilteredIDS = array_column(json_decode($Filtered,true),'id');}
            }   
        } 
        if(!is_null($address)){
            if(is_null($FilteredIDS)){
                $Filtered = DB::table('schools')
                            ->where('address', 'like', '%' . $address . '%')
                            ->orderBy('rating','desc')
                            ->paginate(10);
                if(!is_null($FilteredIDS)){ $FilteredIDS = array_column(json_decode($Filtered,true),'id');}
            }else{
            $Filtered = DB::table('schools')
                            ->where('address', 'like', '%' . $address . '%')
                            ->whereIn('id', $FilteredIDS)
                            ->orderBy('rating','desc')
                            ->paginate(10);
                if(!is_null($FilteredIDS)){ $FilteredIDS = array_column(json_decode($Filtered,true),'id');}
            }   
        } 
       
        if(!is_null($certificate)){
            if(is_null($FilteredIDS)){
                $Filtered = DB::table('school_certificates')
                            ->where('certificate', $certificate )
                            ->get();
                if(!is_null($FilteredIDS)){ $FilteredIDS = array_column(json_decode($Filtered,true),'id');}
                $Filtered = DB::table('schools')
                            ->where('id', $FilteredIDS)
                            ->orderBy('rating','desc')
                            ->paginate(10);
            }else{
                $Filtered = DB::table('school_certificates')
                            ->where('certificate', $certificate )
                            ->whereIn('school_id', $FilteredIDS)
                            ->get();
                if(!is_null($FilteredIDS)){ $FilteredIDS = array_column(json_decode($Filtered,true),'id');}
                $Filtered = DB::table('schools')
                            ->where('id', $FilteredIDS)
                            ->orderBy('rating','desc')
                            ->paginate(10);

            }   
        }
        
        if(!is_null($stage)){
            if(is_null($FilteredIDS)){
                $Filtered = DB::table('school_stages')
                            ->where('stage', $stage )
                            ->get();
                if(!is_null($FilteredIDS)){ $FilteredIDS = array_column(json_decode($Filtered,true),'id');}
                $Filtered = DB::table('schools')
                            ->where('id', $FilteredIDS)
                            ->orderBy('rating','desc')
                            ->paginate(10);
            }else{
                $Filtered = DB::table('school_stages')
                            ->where('stage', $stage )
                            ->whereIn('school_id', $FilteredIDS)
                            ->get();
                if(!is_null($FilteredIDS)){ $FilteredIDS = array_column(json_decode($Filtered,true),'id');}
                $Filtered = DB::table('schools')
                            ->where('id', $FilteredIDS)
                            ->orderBy('rating','desc')
                            ->paginate(10);
            }   
        }
        return Response()->json([
            'Schools' => $Filtered
        ], 200);
    }

}
