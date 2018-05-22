<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Auth;

class DashboardmainController extends Controller {

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
    public function index() { 
        //$region_id = Auth::user()->REGION_ID;
        $category = DB::table('tbl_turnout')->select(DB::raw('category'))->groupBy('category')->get();
       
        
        return view('generateturnout.index',['region' => $region]);
    }  
    
   
}    