<?php

namespace App\Http\Controllers;

//use Auth;
use Illuminate\Http\Request;
//use Illuminate\Routing\UrlGenerator;
//use Illuminate\Routing\Redirector;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\CashGrant;
use App\Models\TblNonCompliantFds;


class ListFdsController extends Controller {

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
        $currentYear = date('Y');         
        $period = [];        
        $hh_status = [];
        $ip = [];
        $sex = [];
        $month = [];
        
        $pathFilters = \Config::get('constants.path_filters_data');
        $model = \App\Models\FiltersFds::all();
        foreach($model AS $r){
            $year[] = $r->year;
            $period = array_merge($period,json_decode($r->period));            
            $hh_status = array_merge($hh_status,json_decode($r->hh_status));
            $ip = array_merge($ip,json_decode($r->ip));
            $sex = array_merge($sex,json_decode($r->sex));
            $month = array_merge($month,json_decode($r->month));                    
        }         
        
        $_period = array_unique($period);        
        $_hh_status = array_unique($hh_status);
        $_ip = array_unique($ip);
        $_sex = array_unique($sex);
        $_month = array_unique($month);        
                 
        sort($year);
        sort($_period);        
        sort($_hh_status);
        sort($_ip);
        sort($_sex);
        sort($_month);  
        
        $_region = \App\Models\LibRegions::all();  
        return view('listfds.index',[            
            '_period' => $_period,
            '_region' => $_region,            
            '_hh_status' => $_hh_status,
            '_ip' => $_ip,
            '_sex' => $_sex,
            '_month' => $_month,
            '_year' => $year,
            '_currentyear' => $currentYear
        ]); 
    }
    public function filter(Request $request){          
        $registration = $finalremarks = $program = $set = $bank = $periodcover = $modepayment = ['-'];        
        $filters = \App\Filters::whereIn('year',$request->id)->get();        
        if(!empty($filters)){
            foreach($filters AS $r){                                
                $jsonModePayment = json_decode($r->modepayment);
                $jsonPeriodCover = json_decode($r->period_cover);
                $jsonBank = json_decode($r->bank);
                $jsonSet = json_decode($r->set);
                $jsonProgram = json_decode($r->program);
                $jsonFinalRemarks = json_decode($r->final_remarks);
                $jsonRegistration = json_decode($r->registration);                

                $modepayment = array_merge($modepayment,$jsonModePayment);
                $periodcover = array_merge($periodcover,$jsonPeriodCover);
                $bank = array_merge($bank,$jsonBank);
                $set = array_merge($set,$jsonSet);
                $program = array_merge($program,$jsonProgram);
                $finalremarks = array_merge($finalremarks,$jsonFinalRemarks);
                $registration = array_merge($registration,$jsonRegistration);
            }
        }      
        $modepayment = array_unique($modepayment);
        $periodcover = array_unique($periodcover);
        $bank = array_unique($bank);
        $set = array_unique($set);
        $program = array_unique($program);
        $finalremarks = array_unique($finalremarks);
        $registration = array_unique($registration);
        sort($modepayment);
        sort($periodcover);
        sort($bank);
        sort($set);
        sort($program);
        sort($finalremarks);
        sort($registration);          
        return response()->json([
            'modepayment' => $modepayment,
            'periodcover' => $periodcover,
            'bank' => $bank,
            'set' => $set,
            'program' => $program,
            'finalremarks' => $finalremarks,
            'registration' => $registration,
        ]);                  
    }
    public function getProvince(Request $request){
        $list = null;
        if($request->id!='null'){            
            $model = new \App\Models\LibProvinces();        
            $l = $model->whereIn('REGION_ID',$request->id)->orderBy('PROVINCE_NAME')->get();        
            $list = $l->toArray();
        }
        return response()->json(['list' => $list]);
    }
    public function getMunicipality(Request $request){  
        $list = null;
        if($request->id!='null'){                       
            $model = new \App\Models\LibCities();        
            $l = $model->whereIn('PROVINCE_ID',$request->id)->orderBy('CITY_NAME')->get();        
            $list = $l->toArray();
        }
        return response()->json(['list' => $list]);
    }
    public function getBrgy(Request $request){  
        $list = null;
        if($request->id!='null'){            
            $model = new \App\Models\LibBrgy();        
            $l = $model->whereIn('CITY_ID',$request->id)->orderBy('BRGY_NAME')->get();        
            $list = $l->toArray();
        }
        return response()->json(['list' => $list]);
    }       
    public function search(Request $request){
        $counter = 1;
        $list = [];            
        $data = [];

        if($request->limit=='' AND $request->limit==null):
            $request->limit = \Config::get('constants.page_limit');
        endif;
        if($request->page=='' AND $request->page==null AND $request->page == 0):
            $request->page = 1;
        endif;        
        $offset = ($request->page - 1) * $request->limit;   
        $counter = $offset + 1;        
        $model = new TblNonCompliantFds();        
        $model->search = [  
            'REGION_ID' => $request->region,
            'PROVINCE_ID' => $request->province,
            'CITY_ID' => $request->muni,
            'BRGY_ID' => $request->brgy,             
            'hh_status' => $request->hh_status,
            'hh_id' => $request->hh_id,
            'entry_id' => $request->entry_id,            
            'lastname' => $request->lastname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'ext' => $request->ext,
            'birthday' => $request->birthday,
            'ip' => $request->ip,
            'sex' => $request->sex,
            'month' => $request->month,
            'year' => $request->year,
            'period' => $request->period,                 
            'page' => $request->page,            
            'order' => $request->order,
            'sort' => $request->sort,
            'limit' => $request->limit,
            'select' => '',
            'count' => false,
        ];                          
        $data = $model->getData();        
        $request->session()->put('listnoncomplianfds', $model->search);        
        if(!empty($data)){            
            //$result = collect($data)->map(function($x){ return (array) $x; })->toArray(); 
            foreach($data AS $r){
                $list[] = [                    
                    'counter' => $counter,                    
                    'region' => $r->REGION_NAME,
                    'province' => $r->PROVINCE_NAME,
                    'muni' => $r->CITY_NAME,
                    'brgy' => $r->BRGY_NAME,                                        
                    'psgc' => $r->psgc,
                    'hh_status' => $r->hh_status,
                    'hh_id' => $r->hh_id,
                    'entry_id' => $r->entry_id,
                    'lastname' => $r->lastname,
                    'firstname' => $r->firstname,
                    'middlename' => $r->middlename,
                    'ext' => $r->ext,  
                    'birthday' => $r->birthday,
                    'ip' => $r->ip,
                    'sex' => $r->sex,
                    'month' => $r->month,                    
                    'year' => $r->year,
                    'period' => $r->period,
                ];
                $counter++;
            }
        }    
        $total = 0;
        $foundSession = false;
        $cntlr = $request->session()->get('controller.listnoncompliantfds',null); 
        if($request->page==1){
            $foundSession = true;
            $model->search['count'] = true;                        
            $cntlr = $model->getData();                               
            $request->session()->put('controller.listnoncompliantfds',$cntlr);            
        }    
        if(!empty($cntlr)):
            foreach($cntlr AS $r):
                $total += $r->total;
            endforeach;
        endif;
        return response()->json([
            'tableData' => $list,
            'rows' => number_format($total),
            'pages' => ceil(intval($total) / $request->limit),
            'pageActive' => intval($request->page),
        ]);        
    }      
    public function showSummary(Request $request){
        $search = $request->session()->get('cashgrantQuery');
        $search['count'] = true;
        $search['select'] = ['COUNT(*) AS total'];
        $grants = new CashGrant();        
        $grants->search = $search;   
        $data = $grants->getData();                       
        $totalRecords = 0;        
        if(!empty($data)){
            foreach($data AS $r){
                $totalRecords += $r->total;
            }
        }        
        $period = 'All';    
        if($search['period_cover']!=null AND is_array($search['period_cover'])){                        
            $period = implode(',',$search['period_cover']);
        }        
        return view('cashgrant.summary',[            
            'totalRecords' => number_format($totalRecords),
            'year' => implode(',',$search['year']),
            'period' => $period,
        ]);
    } 
     public function rebuildfilter(){
        \ini_set('memory_limit','-1');
        \ini_set('max_execution_time', 0); 
    
        $table = 'tbl_noncomp_fds_';
        $config = \App\Config::getValue(['PERIOD_START','PERIOD_CURRENT']);        
        $year = $config['PERIOD_CURRENT'];        
                
        $column = 'period';
        $period = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();
        foreach($result AS $r){
            $period[] = $r->{$column};
        }

        $column = 'hh_status';
        $hh_status = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $hh_status[] = $r->{$column};
        }  

        $column = 'ip';
        $ip = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $ip[] = $r->{$column};
        }  
        
        $column = 'sex';
        $sex = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $sex[] = $r->{$column};
        }

        $column = 'month';
        $month = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $month[] = $r->{$column};
        }          
        
        $data = [    
            'id' => null,            
            'year' => $year,            
            'period' => json_encode($period),            
            'region' => '[]', // json_encode($region),
            'province' => '[]', // json_encode($province),
            'municipality' => '[]', // json_encode($municipality),            
            'hh_status' => json_encode($hh_status),
            'ip' => json_encode($ip),
            'sex' => json_encode($sex),            
            'month' => json_encode($month),            
        ];
                
        $filters = new \App\Models\FiltersFds();
        $return = $filters->where('year',$year)->first();        
        if(empty($return)){            
            $id = $filters->insertGetId($data);              
        }else{   
            unset($data['id']);
            unset($data['year']);
            $filters->where('year',$year)->update($data);
        }
    }          
}
