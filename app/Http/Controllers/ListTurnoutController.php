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
        $region = [];
        $province = [];
        $city = [];
        $brgy = [];
        $psgc_brgy = [];        
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
            $period = array_merge($period,json_decode($r->period));
            $region = array_merge($region,json_decode($r->region));
            $province = array_merge($province,json_decode($r->province));
            $city = array_merge($city,json_decode($r->city));
            $brgy = array_merge($brgy,json_decode($r->brgy));
            $psgc_brgy = array_merge($psgc_brgy,json_decode($r->psgc_brgy));
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
        $_region = array_unique($region);
        $_province = array_unique($province);
        $_municipality = array_unique($municipality);
        $_brgy = array_unique($brgy);    
        $_psgc_brgy = array_unique($psgc_brgy);
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
        sort($_region);
        sort($_province);
        sort($_municipality);
        sort($_brgy);        

        sort($_psgc_brgy);
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
        
        
                
        return view('listturnout.index',[            
            '_period' => $_period,
            '_region' => $_region,
            '_province' => $_province,
            '_municipality' => $_municipality,
            '_brgy' => $_brgy,
            '_psgc' => $_psgc,
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
            'region' => $request->region,
            'province' => $request->province,
            'municipality' => $request->municipality,
            'brgy' => $request->brgy,           
            'psgc' => $request->psgc,
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
                    'region' => $r->region,
                    'province' => $r->province,
                    'municipality' => $r->municipality,
                    'brgy' => $r->brgy,
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
    
        $table = 'tbl_noncomp_fds_';
        $config = \App\Config::getValue(['PERIOD_START','PERIOD_CURRENT']);        
        $year = $config['PERIOD_CURRENT'];        
                
        $column = 'period';
        $period = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();
        foreach($result AS $r){
            $period[] = $r->{$column};
        }

        $column = 'region';
        $region = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();
        foreach($result AS $r){
            $region[] = $r->{$column};
        }
        
        $column = 'province';
        $province = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();
        foreach($result AS $r){
            $province[] = $r->{$column};
        }
        
        $column = 'municipality';
        $municipality = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();
        foreach($result AS $r){
            $municipality[] = $r->{$column};
        }
        
        $column = 'brgy';
        $brgy = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();
        foreach($result AS $r){
            $brgy[] = $r->{$column};
        }
                
        $column = 'psgc';
        $psgc = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();
        foreach($result AS $r){
            $psgc[] = $r->{$column};
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
            'region' => json_encode($region),
            'province' => json_encode($province),
            'municipality' => json_encode($municipality),            
            'brgy' => json_encode($brgy),
            'psgc' => json_encode($psgc),            
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
