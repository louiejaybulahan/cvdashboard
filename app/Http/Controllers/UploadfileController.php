<?php
namespace App\Http\Controllers;

//ini_set('memory_limit','256MB');

//require 'vendor/phpoffice/phpexcel/Classes/PHPExcel.php';

//use Auth;
use Illuminate\Http\Request;

//use Illuminate\Support\Facades\Validator;
//use Illuminate\Routing\UrlGenerator;
//use Illuminate\Routing\Redirector;
//use Illuminate\Support\Facades\Schema;
//use Illuminate\Database\Schema\Blueprint;
//use App\Config;
//use PhpOffice\PhpSpreadsheet\Spreadsheet;
//use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
//use PHPExcel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PHPExcel_Reader_Excel2007;

use Validator;

class UploadfileController extends Controller {

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
        return view('uploadfile.index');
    }       
    // upload multiple files
    public function loadfile(Request $request){        
        $flag = 0;
        $errors = []; 
        $session = [];
        $validate = Validator::make($request->all(), [ 'multipleCompressFileTurnout' => 'required']);
        $request->session()->forget('uploadbasefile');
        if(!$validate->fails()){                
            $uploaded = $request->file('multipleCompressFileTurnout');                
            foreach($uploaded AS $file){
                $validate = Validator::make(['extention'=> strtolower($file->getClientOriginalExtension())],['extention' =>  'in:csv']);                    
                if(!$validate->fails()){                                            
                    $newFilename = date('Ymd').'_'.str_random(20).'.'.$file->getClientOriginalExtension();
                    $destination = config('constants.path_uploaded_data');            
                    $file->move($destination,$newFilename);
                    $session['list'][] = [
                        'filename'  => $newFilename,
                        'extention' => $file->getClientOriginalExtension()
                    ];
                }else{ 
                    $arr = [];
                    foreach($validate->errors()->all() AS $r ){
                        $arr[] = $file->getClientOriginalName() . ' : '. $r;
                    }
                    $errors = array_merge($errors, $arr);
                }
            }            
            if(empty($errors)){
                $flag = 1;
                $session['numberOfFiles'] = count($session['list']);
                $session['position'] = 0;
                $session['startTime'] = microtime(true); 
                $session['limit'] = 2000;                
                $session['currentRow'] = 1;
                $session['option'] = $request->input('option');
                $session['period'] = $request->input('period');
                $session['year'] = $request->input('year');
                $request->session()->put('uploadbasefile',$session);
            }
        }else{ $errors = $validate->errors()->all(); }                      
        return response()->json(['flag' => $flag,'msg' => 'Successfully Save', 'error' => $errors]);
    }   

    // single file upload using jquery
    /*
    public function loadfile(Request $request){   
        $flag = 0;
        $newFilename = $message = '';
        $errors = []; 
        $postRequest = $request->all();
        $file = $request->file('multipleCompressFileTurnout');
        $postRequest['extention'] = strtolower($file->getClientOriginalExtension());                
        $validate = Validator::make($postRequest, [ 
            'multipleCompressFileTurnout' => 'required|max:204810',
            'extention' =>  'in:xlsx,xls'
        ]);        
        if(!$validate->fails()){                            
            $newFilename = date('Ymd').'_'.str_random(20).'.'.$file->getClientOriginalExtension();
            $destination = config('constants.path_uploaded_data');            
            $file->move($destination,$newFilename);     
            $flag = 1;
            $message = 'Successfully Save';
        }else{ $errors = $validate->errors()->all(); }
        return response()->json([
            'flag' => $flag,
            'msg' => $message,
            'error' => $errors,
            'filename' => $newFilename,
        ]);
    }
    */
    public function renderFile(Request $request){    
        \ini_set("memory_limit",-1);                 
        $destination = config('constants.path_uploaded_data'); 
        $session = $request->session()->get('uploadbasefile');  
        $path = null;
        $extention = '';
        $isDone = 0;
        if($session['position']<$session['numberOfFiles']){
            $index = $session['position'];
            $extention = $session['list'][intval($index)]['extention'];
            $path = $destination.'/'.$session['list'][intval($index)]['filename'];         
        }else{ $isDone = 1; }      
        
        return view('uploadfile.output',[
           'destination' => $destination,           
           'extention' => $extention,
           'path' => $path,        
           'sesdata' => $session,
           'isDone' => $isDone
        ]);             
    }
}
