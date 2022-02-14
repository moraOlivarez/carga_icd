<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Load_ICD\LoadManager;

class SearchController extends Controller
{
    private $dir = null;
    private $dir_to_relocate = null;
    private $agreement = null;

    public function index(){
        return view('Agreement.index');
    }

    // search: find in files
    // ctl + shf + f
    public function search(Request $request){

        $request->validate(['location'    => 'required|string|max:255'],
                           ['relocation'  => 'required|string|max:255'],
                           ['agreement'   => 'required|string|max:255']  );
         
        $start = microtime(true);
                         
                         
        $result = new LoadManager();
        if( $request){

            $dir = $request->location;
            $dir_to_relocate =  $request->relocation;
            $agreemen = $request->agreement;


          
         $x =   $result->actionResult( $dir, $dir_to_relocate, $agreemen);
       
        $execution_time = ( microtime(true) - $start);
        if(is_null($x)){
          
            return response()->json([ "result" => null, "execution" => $execution_time]);
        }
        return response()->json([ "result" =>  $x, "execution" => $execution_time]);
        }                 
    
       
    }
    
   
   
}
