<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
//use Illuminate\Routing\UrlGenerator;
//use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;


class SiteController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {      

		$category = DB::table('tbl_turnout')->select(DB::raw('category'))->groupBy('category')->get();	
        return view('site.site',['category'=>$category]);               
    }
}
