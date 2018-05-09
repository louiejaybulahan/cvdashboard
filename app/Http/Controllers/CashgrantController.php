<?php

namespace App\Http\Controllers;

//use Auth;
//use Illuminate\Routing\UrlGenerator;
//use Illuminate\Routing\Redirector;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\CashGrant;


class CashgrantController extends Controller {

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
        $currentYear = \App\Config::getValue('PERIOD_CURRENT');
        $province = \App\Province::orderBy('name')->get();
        $standard = \App\StandardRemarks::orderBy('remarks')->get();    
        $filters = \App\Filters::all();        
        $year = [];
        $registration = $finalremarks = $program = $set = $bank = $periodcover = $modepayment = ['-'];
        if(!empty($filters)){
            $request->session()->put('grantsFilter', $filters->toArray());
            $request->session()->put('currentYear', $currentYear);
            foreach($filters AS $r){                
                $year = array_merge($year,[$r->year]);
                if($r->year==$currentYear){
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
        }               
        $modepayment = array_unique($modepayment);
        $periodcover = array_unique($periodcover);
        $bank = array_unique($bank);
        $set = array_unique($set);
        $program = array_unique($program);
        $finalremarks = array_unique($finalremarks);
        $registration = array_unique($registration);
        sort($year);
        sort($modepayment);
        sort($periodcover);
        sort($bank);
        sort($set);
        sort($program);
        sort($finalremarks);
        sort($registration);     
        return view('cashgrant.index', [
            'province' => $province,
            'standard' => $standard,   
            'year' => $year,
            'modepayment' => $modepayment,
            'periodcover' => $periodcover,
            'bank' => $bank,
            'set' => $set,
            'program' => $program,
            'finalremarks' => $finalremarks,
            'registration' => $registration,
            'current_year' => $currentYear,            
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
    public function rebuildfilter(){
        $config = \App\Config::getValue(['PERIOD_START','PERIOD_CURRENT']);        
        $year = $config['PERIOD_CURRENT'];         
        $column = 'mop';
        $mop = [];
        $result = DB::table('cash_grant_'.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){                        
            $mop[] = $r->{$column};
        }        
        
        $column = 'period_cover';
        $period_cover = [];
        $result = DB::table('cash_grant_'.$year)->select($column)->groupBy($column)->get();
        foreach($result AS $r){
            $periodcover[] = $r->{$column};
        }
              
        $column = 'lbp_branch';
        $lbp_branch = [];
        $result = DB::table('cash_grant_'.$year)->select($column)->groupBy($column)->get();
        foreach($result AS $r){
            $lbp_branch[] = $r->{$column};
        }
        
        $column = 'set_no';
        $set_no = [];
        $result = DB::table('cash_grant_'.$year)->select($column)->groupBy($column)->get();
        foreach($result AS $r){
            $set_no[] = $r->{$column};
        }
        
        $column = 'type_of_program';
        $type_of_program = [];
        $result = DB::table('cash_grant_'.$year)->select($column)->groupBy($column)->get();
        foreach($result AS $r){
            $type_of_program[] = $r->{$column};
        }
                
        $column = 'remarks';
        $remarks = [];
        $result = DB::table('cash_grant_'.$year)->select($column)->groupBy($column)->get();
        foreach($result AS $r){
            $remarks[] = $r->{$column};
        }
        
        $column = 'type_registration';
        $type_registration = [];
        $result = DB::table('cash_grant_'.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $type_registration[] = $r->{$column};
        }  
        
        $data = [            
            'year' => $year,
            'modepayment' => json_encode($mop),
            'period_cover' => json_encode($periodcover),
            'bank' => json_encode($lbp_branch),
            'set' => json_encode($set_no),
            'program' => json_encode($type_of_program),            
            'final_remarks' => json_encode($remarks),
            'registration' => json_encode($type_registration),
        ];
                
        $filters = new \App\Filters();
        $return = $filters->where('year',$year)->first();        
        if(empty($return)){            
            $id = $filters->insertGetId($data);              
        }else{            
            $filters->where('year',$year)->update($data);
        }
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
        $grants = new CashGrant();        
        $grants->search = [  
            'household' => $request->household,
            'lastname' => $request->lastname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'year' => $request->year,
            'province' => $request->province,
            'municipal' => $request->municipal,
            'brgy' => $request->brgy,
            'period_cover' => $request->period,
            'modepayment' => $request->modepayment,
            'regspec' => $request->regspec,
            'bank' => $request->bank,
            'set' => $request->set,
            'program' => $request->program,
            'payroll' => $request->payroll,
            'remarks' => $request->remarks,
            'standard' => $request->standard,
            'registration' => $request->registration,
            'paid' => $request->paid,
            'datefrom' => $request->datefrom,
            'dateto' => $request->dateto,
            'page' => $request->page,            
            'order' => $request->order,
            'sort' => $request->sort,
            'limit' => $request->limit,
            'select' => '',
            'count' => false,
        ];       

        $data = $grants->getData();        
        $request->session()->put('cashgrantQuery', $grants->search);
        //if(!$data->isEmpty()){                
        if(!empty($data)){            
            //$result = collect($data)->map(function($x){ return (array) $x; })->toArray(); 
            foreach($data AS $r){
                $list[] = [                    
                    'counter' => $counter,
                    'householdid' => $r->hh_id,
                    'lastname' => $r->lname,
                    'firstname' => $r->fname,
                    'middlename' => $r->mname,
                    'paid' => $r->paid,
                    'finalremarks' => $r->remarks,
                    'standardremarks' => $r->standard_remarks,
                    'specificremarks' => $r->specific_remarks,
                    'periodcover' => $r->period_cover,
                    'totalhealth' => number_format($r->total_health,2),
                    'rice' => number_format($r->rice),
                    '35daycare' => number_format($r->educ_3_5),  
                    '614hseduc' => number_format($r->educ_hs_6_14),
                    '1518elemeduc' => number_format($r->educ_elem_15_18),
                    '1614elemeduc' => number_format($r->educ_6_14),
                    '1518hseduc' => number_format($r->educ_hs_15_18),
                    '1517educ' => number_format($r->educ_15_17),
                    'grandeduc' => number_format($r->tot_educ),
                    'grandtotal' => number_format($r->grand_tot,2),
                    'typeprogram' => $r->type_of_program,
                    'mop' => $r->mop,
                    'set' => $r->set_no,
                    'dateprocess' => '',
                    'province' => $r->province,
                    'city' => $r->city_mun_fields,  
                    'brgy' => $r->brgy,
                    'datepaid' => '',
                    'regspec' => $r->regular_special_etc,
                    'lbp' => $r->lbp_branch,
                    'typereg' => $r->type_registration,
                    'worker' => $r->username,
                    'napa' => '',
                    'cnt1517' => '',
                    'y' => $r->y,
                ];
                $counter++;
            }
        }    
        $total = 0;
        $foundSession = false;
        $cntlrGrants = $request->session()->get('controller.grants',null); 
        if($request->page==1){
            $foundSession = true;
            $grants->search['count'] = true;                        
            $cntlrGrants = $grants->getData();                               
            $request->session()->put('controller.grants',$cntlrGrants);            
        }    
        if(!empty($cntlrGrants)):
            foreach($cntlrGrants AS $r):
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
    public function testing(Request $request){        
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
    public function paidUnpaid(Request $request){
        $msg = $request->id;
        return response()->json([
            'msg' => $msg,
        ]);     
    }
    public function remarks(){
        $msg = $request->id;
        return response()->json([
            'msg' => $msg,
        ]);     
    }
}
