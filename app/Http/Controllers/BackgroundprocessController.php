<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Helpers\AppTools;
use App\Models\Processlist;

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
            $history = [
                'date_last_executed' => date('Y-m-d'),
                'time_last_executed' => date('h:i:s A'),
                'ip' => AppTools::getLocalIpAddress()
            ];                          
            $model->scriptname = $request->scriptname;
            $model->url = $request->url;
            $model->parameters = $request->parameters;
            $model->run_in = $request->run_in;
            $model->time = $request->time;      
            $model->history = json_encode($history);      
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
    public function checkscript(Request $request){
        $getFirstRow = null;
        $info = [];        
        $detect = $element = 0;
        $index = intval($request->row);        
        $list = \App\Models\Processlist::all();  
        foreach($list AS $r){
            $info[] = [
                'scriptname' => $r->scriptname,
                'url' => $r->url,
                'parameters' => $r->parameters,
                'run_in' => $r->run_in,
                'time' => $r->time,
                'status' => $r->status,
                'history' => json_decode($r->history)
            ];
            if($index<$element){
                $detect = $element;
            }
            $element++;
        }         
        if(!$detect) $index = $detect;        
        $index++;
        return response()->json([
            'rowIndex' => $index,
            'scriptname' => $info[$detect]['scriptname'],
            'url' => $info[$detect]['url'],
            'parameters' => $info[$detect]['parameters'],
            'run_in' => $info[$detect]['run_in'],
            'time' => $info[$detect]['time'],
            'status' => $info[$detect]['status'],
            'history' => $info[$detect]['history']
        ]);
    }  
    public function status(Request $request){
        $flag = 0;
        $status = 0;        
        $model = Processlist::find($request->id);
        $status = ($request->status==0)?1:0;
        $model->status = $status;
        if($model->save()){ $msg = 'Successfully Done!. Status Change'; }
        else{ $msg = 'Error Request!. Invalid to change status'; }
        return response()->json(['flag' => $flag,'msg' => $msg,'status' => $status]);
    }     
}
