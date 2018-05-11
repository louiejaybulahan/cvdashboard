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


class ListTurnoutController extends Controller {

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
        $category = [];
        $set = [];
        $setgroup = [];
        $eligibility = [];
        $not_attend_dominant = [];
        $attend_dominant = [];
        $attend_del_moninant = [];
        $outside = [];
        $monitored_dominant = [];
        $encoded_approved = [];
        $submitted_deworming = [];
        $not_encoded_under_forcem = [];
        $non_compliant = [];
        $compliant = [];
        $remarks_1 = [];
        $remarks_2 = [];
        $remarks_3 = [];
        $remarks_4 = [];
        $client_status = [];        
        $grade_group = [];                
        $sex = [];
        $month = [];        
        $ip = [];
            
        $model = \App\Models\FiltersTurnout::all();
        foreach($model AS $r){
            $year[] = $r->year;                        
            $category = array_merge($category,json_decode($r->category));
            $set = array_merge($set,json_decode($r->set));
            $setgroup = array_merge($setgroup,json_decode($r->setgroup));
            $eligibility = array_merge($eligibility,json_decode($r->eligibility));
            $not_attend_dominant = array_merge($not_attend_dominant,json_decode($r->not_attend_dominant));
            $attend_dominant = array_merge($attend_dominant,json_decode($r->attend_dominant));
            $attend_del_moninant = array_merge($attend_del_moninant,json_decode($r->attend_del_moninant));
            $outside = array_merge($outside,json_decode($r->outside));
            $monitored_dominant = array_merge($monitored_dominant,json_decode($r->monitored_dominant));
            $encoded_approved = array_merge($encoded_approved,json_decode($r->encoded_approved));
            $submitted_deworming = array_merge($submitted_deworming,json_decode($r->submitted_deworming));
            $not_encoded_under_forcem = array_merge($not_encoded_under_forcem,json_decode($r->not_encoded_under_forcem));
            $non_compliant = array_merge($non_compliant,json_decode($r->non_compliant));
            $compliant = array_merge($compliant,json_decode($r->compliant));            
            $remarks_1 = array_merge($remarks_1,json_decode($r->remarks_1));
            $remarks_2 = array_merge($remarks_2,json_decode($r->remarks_2));
            $remarks_3 = array_merge($remarks_3,json_decode($r->remarks_3));
            $remarks_4 = array_merge($remarks_4,json_decode($r->remarks_4));
            $month = array_merge($month,json_decode($r->month));
            $client_status = array_merge($client_status,json_decode($r->client_status));
            $sex = array_merge($sex,json_decode($r->sex));
            $grade_group = array_merge($grade_group,json_decode($r->grade_group));
            $ip = array_merge($ip,json_decode($r->ip));
        }         
        
        $_period = array_unique($period);               
        $_category = array_unique($category);
        $_set = array_unique($set);
        $_setgroup = array_unique($setgroup);
        $_eligibility = array_unique($eligibility);
        $_not_attend_dominant = array_unique($not_attend_dominant);
        $_attend_dominant = array_unique($attend_dominant);
        $_attend_del_moninant = array_unique($attend_del_moninant);
        $_outside = array_unique($outside);
        $_monitored_dominant = array_unique($monitored_dominant);
        $_encoded_approved = array_unique($encoded_approved);
        $_submitted_deworming = array_unique($submitted_deworming);
        $_not_encoded_under_forcem = array_unique($not_encoded_under_forcem);
        $_non_compliant = array_unique($non_compliant);
        $_compliant = array_unique($compliant);            
        $_remarks_1 = array_unique($remarks_1);
        $_remarks_2 = array_unique($remarks_2);
        $_remarks_3 = array_unique($remarks_3);
        $_remarks_4 = array_unique($remarks_4);
        $_month = array_unique($month);   
        $_client_status = array_unique($client_status);
        $_sex = array_unique($sex);
        $_grade_group = array_unique($grade_group);
        $_ip = array_unique($ip);
                         
        sort($year);
        sort($_period);        
        sort($_category);
        sort($_set);
        sort($_setgroup);
        sort($_eligibility);
        sort($_not_attend_dominant);
        sort($_attend_dominant);
        sort($_outside);
        sort($_monitored_dominant);
        sort($_encoded_approved);
        sort($_submitted_deworming);
        sort($_not_encoded_under_forcem);
        sort($_non_compliant);
        sort($_compliant);
        sort($_remarks_1);
        sort($_remarks_2);
        sort($_remarks_3);
        sort($_remarks_4);                
        sort($_month);  
        sort($_client_status);
        sort($_sex);
        sort($_grade_group);
        sort($_ip);
        
        
        $_region = \App\Models\LibRegions::all();           
        return view('listturnout.index',[            
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
    public function city(Request $request){  
        $list = null;
        if($request->id!='null'){
            $muni = new \App\CityMuni();        
            $l = $muni->whereIn('prov_id',$request->id)->orderBy('name')->get();        
            $list = $l->toArray();
        }
        return response()->json(['list' => $list]);
    }
    public function brgy(Request $request){  
        $list = null;
        if($request->id!='null'){
            $brgyId = [];
            foreach($request->id AS $r):
                $tmp = explode('|',$r);
                $brgyId[] = $tmp[1];
            endforeach;               
            $model = new \App\Barangay();        
            $l = $model->whereIn('mun_id',$brgyId)->orderBy('name')->get();        
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
        $model->getQuery();
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
        // echo $grants->getQuery();
        // \App\Helpers\AppTools::printArray($search);             
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
    
        $year = 2017;
        $period = 1;
        $table = 'tbl_turnout_'.$year.'_'.$period;
        $config = \App\Config::getValue(['PERIOD_START','PERIOD_CURRENT']);        
        $year = $config['PERIOD_CURRENT'];        
                 
        $column = 'category';
        $category = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $category[] = $r->{$column};
        }  

        $column = 'set';
        $set = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $set[] = $r->{$column};
        }  
        
        $column = 'setgroup';
        $setgroup = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $setgroup[] = $r->{$column};
        }

        $column = 'eligibility';
        $eligibility = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $eligibility[] = $r->{$column};
        }
        
        $column = 'not_attend_dominant';
        $not_attend_dominant = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $not_attend_dominant[] = $r->{$column};
        }  
        
        $column = 'attend_dominant';
        $attend_dominant = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $attend_dominant[] = $r->{$column};
        }  
        
        $column = 'attend_del_dominant';
        $attend_del_dominant = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $attend_del_dominant[] = $r->{$column};
        }  
        
        $column = 'outside';
        $outside = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $outside[] = $r->{$column};
        }  

        $column = 'monitored_dominant';
        $monitored_dominant = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $monitored_dominant[] = $r->{$column};
        }  
        
        $column = 'encoded_approved';
        $encoded_approved = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $encoded_approved[] = $r->{$column};
        }  
        
        $column = 'eligibility';
        $eligibility = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $eligibility[] = $r->{$column};
        }          
        
        $column = 'submitted_deworming';
        $submitted_deworming = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $submitted_deworming[] = $r->{$column};
        }  
        
        $column = 'not_encoded_approved';
        $not_encoded_approved = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $not_encoded_approved[] = $r->{$column};
        }          
        
        $column = 'encoded_under_forcem';
        $encoded_under_forcem = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $encoded_under_forcem[] = $r->{$column};
        }  
        
        $column = 'non_compliant';
        $non_compliant = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $non_compliant[] = $r->{$column};
        }          
        
        $column = 'compliant';
        $compliant = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $compliant[] = $r->{$column};
        }  
        
        $column = 'remarks_1';
        $remarks_1 = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $remarks_1[] = $r->{$column};
        }  
        
        $column = 'remarks_2';
        $remarks_2 = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $remarks_2[] = $r->{$column};
        }     

        $column = 'remarks_3';
        $remarks_3 = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $remarks_3[] = $r->{$column};
        } 
        
        $column = 'remarks_3';
        $remarks_3 = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $remarks_3[] = $r->{$column};
        } 
        
        $column = 'remarks_4';
        $remarks_4 = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $remarks_4[] = $r->{$column};
        } 
        
        $column = 'month';
        $month = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $month[] = $r->{$column};
        }     

        $column = 'client_status';
        $client_status = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $client_status[] = $r->{$column};
        }   

        $column = 'sex';
        $sex = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $sex[] = $r->{$column};
        }            

        $column = 'grade_group';
        $grade_group = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $remarks_2[] = $r->{$column};
        }    
        
        $column = 'ip';
        $ip = [];
        $result = DB::table($table)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $ip[] = $r->{$column};
        }    
                            
        $data = [    
            'id' => null,            
            'year' => $year,            
            'period' => $period,
            'category' => json_encode($category),            
            'set' => json_encode($set),            
            'setgroup' => json_encode($setgroup),            
            'eligibility' => json_encode($eligibility),                                  
            'not_attend_dominant' => json_encode($not_attend_dominant),            
            'attend_dominant' => json_encode($attend_dominant),            
            'attend_del_dominant' => json_encode($attend_del_dominant),            
            'outside' => json_encode($outside),            
            'monitored_dominant' => json_encode($monitored_dominant),            
            'encoded_approved' => json_encode($encoded_approved),            
            'submitted_deworming' => json_encode($submitted_deworming),            
            'not_encoded_approved' => json_encode($not_encoded_approved),            
            'encoded_under_forcem' => json_encode($encoded_under_forcem),            
            'non_compliant' => json_encode($non_compliant),            
            'compliant' => json_encode($compliant),       
            'remarks_1' => json_encode($remarks_1),            
            'remarks_2' => json_encode($remarks_2),            
            'remarks_3' => json_encode($remarks_3),            
            'remarks_4' => json_encode($remarks_4),            
            'month' => json_encode($month),                    
            'client_status' => json_encode($client_status),            
            'sex' => json_encode($sex),            
            'grade_group' => json_encode($grade_group),                
            'ip' => json_encode($ip),                        
        ];
                
        $filters = new \App\Models\FiltersTurnout();
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
