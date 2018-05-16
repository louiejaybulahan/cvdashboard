<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Auth;

class SetperiodactiveController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) { 
        if($request->input('period_id')!=null AND is_numeric($request->input('period_id'))){
            DB::table('lib_periods')->update(['is_status'=>0]);
            DB::table('lib_periods')->where('period_id', $request->input('period_id'))->update(['is_status'=>1]);
        }
        $period = \App\Models\LibPeriod::all();
        return view('periodactive.index',['period'=>$period]);
    }  
    
   
}    