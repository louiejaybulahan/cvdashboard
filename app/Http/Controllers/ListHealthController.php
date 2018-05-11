<?php

namespace App\Http\Controllers;

//use Auth;
use Illuminate\Http\Request;
//use Illuminate\Routing\UrlGenerator;
//use Illuminate\Routing\Redirector;
//use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\CashGrant;
use App\Models\TblNonCompliantHealth;


class ListHealthController extends Controller {

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
        $pregnant = [];
        $child = [];
        $hc_name = [];
        $hc_region = [];
        $hc_province = [];
        $hc_muni = [];
        $hc_brgy = [];
        $dom_hc_name = [];
        $dom_hc_region = [];
        $dom_hc_province = [];
        $dom_hc_muni = [];
        $dom_hc_brgy = [];
        $remarks = [];
        $month = [];
        
        $pathFilters = \Config::get('constants.path_filters_data');
        $model = \App\Models\FiltersHealth::all();
        foreach($model AS $r){
            $year[] = $r->year;
            $period = array_merge($period,json_decode($r->period));          
            $hh_status = array_merge($hh_status,json_decode($r->hh_status));
            $ip = array_merge($ip,json_decode($r->ip));
            $sex = array_merge($sex,json_decode($r->sex));
            $pregnant = array_merge($pregnant,json_decode($r->pregnant));
            $child = array_merge($child,json_decode($r->child));            
            $hc_region = array_merge($hc_region,json_decode($r->hc_region));
            $hc_province = array_merge($hc_province,json_decode($r->hc_province));
            $hc_muni = array_merge($hc_muni,json_decode($r->hc_muni));            
            $dom_hc_region = array_merge($dom_hc_region,json_decode($r->dom_hc_region));
            $dom_hc_province = array_merge($dom_hc_province,json_decode($r->dom_hc_province));
            $dom_hc_muni = array_merge($dom_hc_muni,json_decode($r->dom_hc_muni));            
            $remarks = array_merge($remarks,json_decode($r->remarks));
            $month = array_merge($month,json_decode($r->month));
            

            $f = fopen($pathFilters.'health_'.$r->year.'_hcname.json','r');
            $tmp = fgets($f);
            $hc_name = array_merge($hc_name,json_decode($tmp));
            fclose($f);
            
            $f = fopen($pathFilters.'health_'.$r->year.'_hcbrgy.json','r');
            $tmp = fgets($f);
            $hc_brgy = array_merge($hc_brgy,json_decode($tmp));
            fclose($f);   

            $f = fopen($pathFilters.'health_'.$r->year.'_domhcname.json','r');
            $tmp = fgets($f);
            $dom_hc_name = array_merge($dom_hc_name,json_decode($tmp));
            fclose($f);
            
            $f = fopen($pathFilters.'health_'.$r->year.'_domhcbrgy.json','r');
            $tmp = fgets($f);
            $dom_hc_brgy = array_merge($dom_hc_brgy,json_decode($tmp));
            fclose($f);               
        }         
        
        $_period = array_unique($period);        
        $_hh_status = array_unique($hh_status);
        $_ip = array_unique($ip);
        $_sex = array_unique($sex);
        $_pregnant = array_unique($pregnant);
        $_child = array_unique($child);
        $_hc_name = array_unique($hc_name);
        $_hc_region = array_unique($hc_region);
        $_hc_province = array_unique($hc_province);
        $_hc_muni = array_unique($hc_muni);
        $_hc_brgy = array_unique($hc_brgy);
        $_dom_hc_name = array_unique($dom_hc_name);
        $_dom_hc_region = array_unique($dom_hc_region);
        $_dom_hc_province = array_unique($dom_hc_province);
        $_dom_hc_muni = array_unique($dom_hc_muni);
        $_dom_hc_brgy = array_unique($dom_hc_brgy);
        $_remarks = array_unique($remarks);
        $_month = array_unique($month);        
                 
        sort($year);
        sort($_period);        
        sort($_hh_status);
        sort($_ip);
        sort($_sex);
        sort($_pregnant);
        sort($_child);
        sort($_hc_name);  
        sort($_hc_region);  
        sort($_hc_province);  
        sort($_hc_muni);  
        sort($_hc_brgy);  
        sort($_dom_hc_name);  
        sort($_dom_hc_region);  
        sort($_dom_hc_province);  
        sort($_dom_hc_muni);  
        sort($_dom_hc_brgy);  
        sort($_remarks);  
        sort($_month);  

        $_region = \App\Models\LibRegions::all();         
        return view('listhealth.index',[            
            '_period' => $_period,
            '_region' => $_region,            
            '_hh_status' => $_hh_status,
            '_ip' => $_ip,
            '_sex' => $_sex,
            '_pregnant' => $_pregnant,
            '_child' => $_child,
            '_hc_name' => $_hc_name,
            '_hc_region' => $_hc_region,
            '_hc_province' => $_hc_province,
            '_hc_muni' => $_hc_muni,      
            '_hc_brgy' => $_hc_brgy,
            '_dom_hc_name' => $_dom_hc_name,
            '_dom_hc_region' => $_dom_hc_region,
            '_dom_hc_province' => $_dom_hc_province,
            '_dom_hc_muni' => $_dom_hc_muni,
            '_dom_hc_brgy' => $_dom_hc_brgy,
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
    
        $table = 'tbl_noncomp_health_';
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

        $column = 'pregnant';
        $pregnant = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $pregnant[] = $r->{$column};
        }          

        $column = 'child';
        $child = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $child[] = $r->{$column};
        }          

        $column = 'hc_name';
        $hc_name = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $hc_name[] = $r->{$column};
        }

        $column = 'hc_region';
        $hc_region = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $hc_region[] = $r->{$column};
        }        

        $column = 'hc_province';
        $hc_province = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $hc_province[] = $r->{$column};
        }
                
        $column = 'hc_muni';
        $hc_muni = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $hc_muni[] = $r->{$column};
        }
        
        $column = 'hc_brgy';
        $hc_brgy = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $hc_brgy[] = $r->{$column};
        }
        
        $column = 'dom_hc_name';
        $dom_hc_name = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $dom_hc_name[] = $r->{$column};
        }  

        $column = 'dom_hc_region';
        $dom_hc_region = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $dom_hc_region[] = $r->{$column};
        }  

        $column = 'dom_hc_province';
        $dom_hc_province = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $dom_hc_province[] = $r->{$column};
        }  

        $column = 'dom_hc_muni';
        $dom_hc_muni = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $dom_hc_muni[] = $r->{$column};
        }  

        $column = 'dom_hc_brgy';
        $dom_hc_brgy = [];
        $result = DB::table($table.$year)->select($column)->groupBy($column)->get();        
        foreach($result AS $r){
            $dom_hc_brgy[] = $r->{$column};
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
       
        $f = fopen($pathFilters.'health_'.$year.'_hcname.json','w');
        fwrite($f,json_encode($hc_name));
        fclose($f);

        $f = fopen($pathFilters.'health_'.$year.'_hcbrgy.json','w');
        fwrite($f,json_encode($hc_brgy));
        fclose($f);
        
        $f = fopen($pathFilters.'health_'.$year.'_domhcname.json','w');
        fwrite($f,json_encode($dom_hc_name));
        fclose($f);

        $f = fopen($pathFilters.'health_'.$year.'_domhcbrgy.json','w');
        fwrite($f,json_encode($dom_hc_brgy));
        fclose($f);
                    
        $data = [    
            'id' => null,            
            'year' => $year,            
            'period' => json_encode($period),            
            'region' => '[]', // json_encode($region),
            'province' => '[]', // json_encode($province),
            'muni' => '[]', // json_encode($muni),                                  
            'hh_status' => json_encode($hh_status),
            'ip' => json_encode($ip),
            'sex' => json_encode($sex),            
            'pregnant' => json_encode($pregnant),            
            'child' => json_encode($child),                        
            'hc_region' => json_encode($hc_region),                                   
            'hc_province' => json_encode($hc_province),
            'hc_muni' => json_encode($hc_muni),            
            'dom_hc_region' => json_encode($dom_hc_region),
            'dom_hc_province' => json_encode($dom_hc_province),
            'dom_hc_muni' => json_encode($dom_hc_muni),            
            'remarks' => json_encode($remarks),
            'month' => json_encode($month),            
        ];
                
        $filters = new \App\Models\FiltersHealth();
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
        $model = new TblNonCompliantHealth();        
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
            'grade' => $request->grade,
            'hc_name' => $request->hc_name,
            'hc_region' => $request->hc_region,
            'hc_province' => $request->hc_province,
            'hc_muni' => $request->hc_muni,
            'hc_brgy' => $request->hc_brgy,            
            'dom_hc_name' => $request->dom_hc_name,
            'dom_hc_region' => $request->dom_hc_region,
            'dom_hc_province' => $request->dom_hc_province,
            'dom_hc_muni' => $request->dom_hc_muni,
            'dom_hc_brgy' => $request->dom_hc_brgy,
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
        $data = $model->getData();        
        $request->session()->put('listnoncomplianteducation', $model->search);        
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
                    'pregnant' => $r->pregnant,
                    'child' => $r->child,
                    'hc_id' => $r->hc_id,
                    'hc_name' => $r->hc_name,
                    'hc_region' => $r->hc_region,
                    'hc_province' => $r->hc_province,
                    'hc_muni' => $r->hc_muni,
                    'hc_brgy' => $r->hc_brgy,                    
                    'dom_hc_id' => $r->dom_hc_id,
                    'dom_hc_name' => $r->dom_hc_name,
                    'dom_hc_region' => $r->dom_hc_region,
                    'dom_hc_province' => $r->dom_hc_province,
                    'dom_hc_muni' => $r->dom_hc_muni,
                    'dom_hc_brgy' => $r->dom_hc_brgy,
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
