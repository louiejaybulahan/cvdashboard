<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use \App\Models\Processlist;

class BackgroundprocessController extends Controller {

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
        $list = Processlist::all();                                         
        return view('backgroundprocess.index',[
            'processlist' => $list
        ]);               
    }

    public function addscript(Request $request){        
        $model = new Processlist;        
        $status = 'new'; 
        if ($model->validateForm($request)) {                               
            $model->scriptname = $request->scriptname;
            $model->url = $request->url;
            $model->status = 0;               
            $model->save();
            return response()->json(['flag' => 1,'msg' => 'Successfully Save','token' => csrf_token()]);
        } else return response()->json(['flag' => 0,'status' => $status, 'msg' => 'Invalid Saving!. Pls. fill-up the form correctly',  'errorlist' => $model->getErrorMessage(), 'token' => csrf_token()]);  
    }
    public function remove(Request $request){ 
        $flag = 0;
        if(isset($request->id)){
            $model = new Processlist();
            $result = $model->find(intval($request->id));            
            if($result->delete()){ 
                $flag = 1; 
                $msg = 'Successfully Remove'; 
            }
            else{ $msg = 'Invalid Action'; }
        }else{ $msg = 'Invalid ID'; }                
        return response()->json(['flag' => $flag,'msg' => $msg,'id' => $request->id, 'token' => csrf_token()]);
    }     
    public function loadscript(){        
    }  
    public function readnextscript(){
    }     
}
