<?php

namespace App\Http\Controllers;

// use Auth;
use Illuminate\Http\Request;
//use Illuminate\Routing\UrlGenerator;
//use Illuminate\Routing\Redirector;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
//use Illuminate\Support\Facades\DB;
use App\Models\UserInfo;
//use App\Models\Roles;
//use App\Models\CityMuni;

class UsersController extends Controller {

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
        $user = new UserInfo();        
        $region = \App\Models\LibRegions::all();        
        $userlevel = \App\Models\UserLevel::orderBy('level_name','asc')->get();        
        return view('users.index', [
            'users' => $user->getUserList(),
            'userlevel' => $userlevel,
            'region' => $region,
        ]);
    }
    
    public function save(Request $request) {
        $status = '';        
        if($request->id==''){        
            $user = new UserInfo;
            $user->is_status = 1;
            $status = 'new'; 
        }else{
            $user = UserInfo::find($request->id);
            $status = 'update';
        }       
        if ($user->validateuserInfo($request,$status)) {       
            if($status=='new') $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->lastname = $request->lastname;
            $user->firstname = $request->firstname;
            $user->middlename = '';
            $user->email = $request->email;
            $user->access = '';
            $user->contact = $request->contact;
            $user->REGION_ID = $request->region;
            $user->level_id = $request->userlevel;            
            $user->save();
            return response()->json(['flag' => 1,'msg' => 'Successfully Save','token' => csrf_token()]);
        } else return response()->json(['flag' => 0,'status' => $status, 'msg' => 'Invalid Saving!. Pls. fill-up the form correctly',  'errorlist' => $user->getErrorMessage(), 'token' => csrf_token()]);                
    }        
    public function remove(Request $request){ 
        $flag = 0;
        if(isset($request->id)){
            $user = new UserInfo();
            $result = $user->find(intval($request->id));            
            if($result->delete()){ 
                $flag = 1; 
                $msg = 'Successfully Remove'; 
            }
            else{ $msg = 'Invalid Action'; }
        }else{ $msg = 'Invalid ID'; }                
        return response()->json(['flag' => $flag,'msg' => $msg,'id' => $request->id, 'token' => csrf_token()]);
    }
    public function edit(Request $request){
        $flag = 0;
        $msg = '';
        $info = [];
        if(isset($request->id)){
            $user = new UserInfo();
            $result = $user->find(intval($request->id));
            if(isset($result)){                
                $info = [
                    'id' => $result->user_id,
                    'username' => $result->username,                    
                    'lastname' => $result->lastname,
                    'firstname' => $result->firstname,
                    'email' => $result->email,
                    'contact' => $result->contact,
                    'userlevel' => $result->level_id,
                    'region' => $result->REGION_ID,
                ];
                $flag = 1;
            }else{ $msg = 'Invalid Username'; }
        }else{ $msg = 'Invalid ID'; }                
        return response()->json(['flag' => $flag,'msg' => $msg,'info' => $info, 'token' => csrf_token()]);
    }   
}
