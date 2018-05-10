<?php

namespace App\Http\Controllers;

//use Auth;
use Illuminate\Http\Request;
//use Illuminate\Routing\UrlGenerator;
//use Illuminate\Routing\Redirector;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\CashGrant;
use App\Models\TblNonCompliantEducation;


class ListEducationController extends Controller {

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
        $grade = [];
        $school_name = [];
        $school_region = [];
        $school_province = [];
        $school_muni = [];
        $school_brgy = [];
        $dom_sch_name = [];
        $dom_sch_region = [];
        $dom_sch_province = [];
        $dom_sch_muni = [];
        $dom_sch_brgy = [];
        $remarks = [];
        $month = [];
            
        $pathFilters = \Config::get('constants.path_filters_data');
        $model = \App\Models\FiltersEducation::all();
        foreach($model AS $r){
            $year[] = $r->year;
            $period = array_merge($period,json_decode($r->period));            
            $hh_status = array_merge($hh_status,json_decode($r->hh_status));
            $ip = array_merge($ip,json_decode($r->ip));
            $grade = array_merge($grade,json_decode($r->grade));            
            $school_region = array_merge($school_region,json_decode($r->school_region));
            $school_province = array_merge($school_province,json_decode($r->school_province));
            $school_muni = array_merge($school_muni,json_decode($r->school_muni));            
            $dom_sch_region = array_merge($dom_sch_region,json_decode($r->dom_sch_region));
            $dom_sch_province = array_merge($dom_sch_province,json_decode($r->dom_sch_province));
            $dom_sch_muni = array_merge($dom_sch_muni,json_decode($r->dom_sch_muni));            
            $remarks = array_merge($remarks,json_decode($r->remarks));
            $month = array_merge($month,json_decode($r->month));
                                                
            $f2 = fopen($pathFilters.'education_'.$r->year.'_domschoolbrgy.json','r');
            $tmp = fgets($f2);
            $dom_sch_brgy = array_merge($dom_sch_brgy,json_decode($tmp));
            fclose($f2);
            
            $f3 = fopen($pathFilters.'education_'.$r->year.'_domschoolname.json','r');
            $tmp = fgets($f3);
            $dom_sch_name = array_merge($dom_sch_name,json_decode($tmp));
            fclose($f3);
       
            $f5 = fopen($pathFilters.'education_'.$r->year.'_shoolbrgy.json','r');
            $tmp = fgets($f5);
            $school_brgy = array_merge($school_brgy,json_decode($tmp));
            fclose($f5);
            
            $f6 = fopen($pathFilters.'education_'.$r->year.'_shoolname.json','r');
            $tmp = fgets($f6);
            $school_name = array_merge($school_name,json_decode($tmp));
            fclose($f6);
        }              
                        
        $_period = array_unique($period);        
        $_hh_status = array_unique($hh_status);
        $_ip = array_unique($ip);
        $_grade = array_unique($grade);
        $_school_name = array_unique($school_name);
        $_school_region = array_unique($school_region);
        $_school_province = array_unique($school_province);
        $_school_muni = array_unique($school_muni);
        $_school_brgy = array_unique($school_brgy);
        $_dom_sch_name = array_unique($dom_sch_name);
        $_dom_sch_region = array_unique($dom_sch_region);
        $_dom_sch_province = array_unique($dom_sch_province);
        $_dom_sch_muni = array_unique($dom_sch_muni);
        $_dom_sch_brgy = array_unique($dom_sch_brgy);
        $_remarks = array_unique($remarks);
        $_month = array_unique($month);        
                 
        sort($year);
        sort($_period);                  
        sort($_hh_status);
        sort($_ip);
        sort($_grade);          
        sort($_school_name);  
        sort($_school_region);  
        sort($_school_province);  
        sort($_school_muni);  
        sort($_school_brgy);  
        sort($_dom_sch_name);  
        sort($_dom_sch_region);  
        sort($_dom_sch_province);  
        sort($_dom_sch_muni);  
        sort($_dom_sch_brgy);  
        sort($_remarks);  
        sort($_month);  

        $_region = \App\Models\LibRegions::all();        
        return view('listeducation.index',[            
            '_period' => $_period,
            '_region' => $_region,                  
            '_hh_status' => $_hh_status,
            '_ip' => $_ip,
            '_grade' => $_grade,
            '_school_name' => $_school_name,
            '_school_region' => $_school_region,
            '_school_province' => $_school_province,
            '_school_muni' => $_school_muni,      
            '_school_brgy' => $_school_brgy,
            '_dom_sch_name' => $_dom_sch_name,
            '_dom_sch_region' => $_dom_sch_region,
            '_dom_sch_province' => $_dom_sch_province,
            '_dom_sch_muni' => $_dom_sch_muni,
            '_dom_sch_brgy' => $_dom_sch_brgy,
            '_remarks' => $_remarks,
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
    public function rebuildfilter(){
         \ini_set('memory_limit','-1');
        \ini_set('max_execution_time', 0); 
    
        $table = 'tbl_noncomp_educ_';
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
        
        $column = 'muni';
        $muni = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();
        foreach($result AS $r){
            $muni[] = $r->{$column};
        }
        
        $column = 'brgy';
        $brgy = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();
        foreach($result AS $r){
            $brgy[] = $r->{$column};
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
        
        $column = 'grade';
        $grade = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $grade[] = $r->{$column};
        }          

        
        $column = 'school_name';
        $school_name = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $school_name[] = $r->{$column};
        }
        

        $column = 'school_region';
        $school_region = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $school_region[] = $r->{$column};
        }        

        $column = 'school_province';
        $school_province = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $school_province[] = $r->{$column};
        }
                
        $column = 'school_muni';
        $school_muni = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $school_muni[] = $r->{$column};
        }
        
        $column = 'school_brgy';
        $school_brgy = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $school_brgy[] = $r->{$column};
        }
        
        $column = 'dom_sch_name';
        $dom_sch_name = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $dom_sch_name[] = $r->{$column};
        }  

        $column = 'dom_sch_region';
        $dom_sch_region = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $dom_sch_region[] = $r->{$column};
        }  

        $column = 'dom_sch_province';
        $dom_sch_province = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $dom_sch_province[] = $r->{$column};
        }  

        $column = 'dom_sch_muni';
        $dom_sch_muni = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $dom_sch_muni[] = $r->{$column};
        }  

        $column = 'dom_sch_brgy';
        $dom_sch_brgy = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $dom_sch_brgy[] = $r->{$column};
        }  

        $column = 'remarks';
        $remarks = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $remarks[] = $r->{$column};
        }  

        $column = 'month';
        $month = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $month[] = $r->{$column};
        }  
        
        $pathFilters = \Config::get('constants.path_filters_data');
        $f = fopen($pathFilters.'education_'.$year.'_brgy.json','w');
        fwrite($f,json_encode($brgy));
        fclose($f);
                       
        $f = fopen($pathFilters.'education_'.$year.'_shoolname.json','w');
        fwrite($f,json_encode($school_name));
        fclose($f);
        
        $f = fopen($pathFilters.'education_'.$year.'_shoolbrgy.json','w');
        fwrite($f,json_encode($school_brgy));
        fclose($f);
               
        $f = fopen($pathFilters.'education_'.$year.'_domschoolname.json','w');
        fwrite($f,json_encode($dom_sch_name));
        fclose($f);
        
        $f = fopen($pathFilters.'education_'.$year.'_domschoolbrgy.json','w');
        fwrite($f,json_encode($dom_sch_brgy));
        fclose($f);
        
        $data = [    
            'id' => null,            
            'year' => $year,            
            'period' => json_encode($period),            
            'region' => json_encode($region),
            'province' => json_encode($province),
            'muni' => json_encode($muni),            
            'hh_status' => json_encode($hh_status),
            'ip' => json_encode($ip),
            'grade' => json_encode($grade),            
            'school_region' => json_encode($school_region),                                   
            'school_province' => json_encode($school_province),
            'school_muni' => json_encode($school_muni),
            'dom_sch_region' => json_encode($dom_sch_region),
            'dom_sch_province' => json_encode($dom_sch_province),
            'dom_sch_muni' => json_encode($dom_sch_muni),
            'remarks' => json_encode($remarks),
            'month' => json_encode($month),            
        ];        
                
        $filters = new \App\Models\FiltersEducation();
        $return = $filters->where('year',$year)->first();        
        if(empty($return)){            
            $id = $filters->insertGetId($data);              
        }else{          
            unset($data['id']);
            unset($data['year']);  
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
        $model = new TblNonCompliantEducation();        
        $model->search = [  
            'region' => $request->region,
            'province' => $request->province,
            'muni' => $request->muni,
            'brgy' => $request->brgy,           
            'hh_status' => $request->hh_status,
            'hh_id' => $request->hh_id,
            'entry_id' => $request->entry_id,            
            'lastname' => $request->lastname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'ext' => $request->ext,
            'bday' => $request->bday,
            'ip' => $request->ip,
            'grade' => $request->grade,
            'school_name' => $request->school_name,
            'school_region' => $request->school_region,
            'school_province' => $request->school_province,
            'school_muni' => $request->school_muni,
            'school_brgy' => $request->school_brgy,            
            'dom_sch_name' => $request->dom_sch_name,
            'dom_sch_region' => $request->dom_sch_region,
            'dom_sch_province' => $request->dom_sch_province,
            'dom_sch_muni' => $request->dom_sch_muni,
            'dom_sch_brgy' => $request->dom_sch_brgy,
            'remarks' => $request->remarks,
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
        // echo $model->getQuery();
        $data = $model->getData();        
        $request->session()->put('listnoncomplianteducation', $model->search);        
        if(!empty($data)){            
            //$result = collect($data)->map(function($x){ return (array) $x; })->toArray(); 
            foreach($data AS $r){
                $list[] = [                    
                    'counter' => $counter,
                    'region' => $r->region,
                    'province' => $r->province,
                    'muni' => $r->muni,
                    'brgy' => $r->brgy,                    
                    'hh_status' => $r->hh_status,
                    'hh_id' => $r->hh_id,
                    'entry_id' => $r->entry_id,
                    'lastname' => $r->lastname,
                    'firstname' => $r->firstname,
                    'middlename' => $r->middlename,
                    'ext' => $r->ext,  
                    'bday' => $r->bday,
                    'ip' => $r->ip,
                    'grade' => $r->grade,
                    'school_id' => $r->school_id,
                    'school_name' => $r->school_name,
                    'school_region' => $r->school_region,
                    'school_province' => $r->school_province,
                    'school_muni' => $r->school_muni,
                    'school_brgy' => $r->school_brgy,                    
                    'dom_sch_id' => $r->dom_sch_id,
                    'dom_sch_name' => $r->dom_sch_name,
                    'dom_sch_region' => $r->dom_sch_region,
                    'dom_sch_province' => $r->dom_sch_province,
                    'dom_sch_muni' => $r->dom_sch_muni,
                    'dom_sch_brgy' => $r->dom_sch_brgy,
                    'remarks' => $r->remarks,
                    'month' => $r->month,                    
                    'year' => $r->year,
                    'period' => $r->period,
                ];
                $counter++;
            }
        }    
        $total = 0;
        $foundSession = false;
        $cntlr = $request->session()->get('controller.listnoncomplianteducation',null); 
        if($request->page==1){
            $foundSession = true;
            $model->search['count'] = true;                        
            $cntlr = $model->getData();                               
            $request->session()->put('controller.listnoncomplianteducation',$cntlr);            
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
}
