<?php

namespace App\Http\Controllers;

//use Auth;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Config;



class SettingsController extends Controller {

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
        $config = Config::getValue(['AUDIT_TRAIL','PERIOD_START','PERIOD_CURRENT']);
        $filter = \App\Filters::all();        
        return view('settings.index', [
            'config' => $config,
            'filters' => $filter->toArray(),
        ]);
    }

    public function save(Request $request){
        $flag = 0;
        $msg = 'Well Done. Successfully save chages!.';
        Config::setValue('AUDIT_TRAIL',$request->configAuditTrail);
        Config::setValue('PERIOD_START',$request->configPeriodStart);
        Config::setValue('PERIOD_CURRENT',$request->configPeriodCurrent);
        return response()->json(['flag' => $flag,'msg' => $msg]);
    }

    public function newstorage(){   
        $year = date('Y');    
        if(!Schema::hasTable('cash_grant_'.$year)){
            Schema::create('cash_grant_'.$year, function (Blueprint $table) {
                    $table->engine = 'MyISAM';                              
                    $table->charset = 'utf8';
                    $table->collation = 'utf8_general_ci';                
                    //$table->primary('trans_no');
                    $table->increments('trans_no'); //`trans_no` int(15) NOT NULL AUTO_INCREMENT COMMENT 'Transaction No. Primary Key Unique',
                    $table->string('hh_id',25);  //`hh_id` varchar(25) CHARACTER SET utf8 NOT NULL COMMENT 'HouseHold ID',
                    $table->string('fname',50);  //`fname` varchar(50) CHARACTER SET utf8 NOT NULL,
                    $table->string('lname',50);  //`lname` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'Last Name',
                    $table->string('mname',50);  //`mname` varchar(50) CHARACTER SET utf8mb4 NOT NULL COMMENT 'Middle Name',
                    $table->string('province',50);  //`province` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'Province',
                    $table->string('city_mun_fields',50);  //`city_mun_fields` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'City / Municipality',
                    $table->string('brgy',50);  //`brgy` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'Barangay',
                    $table->string('period_cover',50)->nullable();  //`period_cover` varchar(50) CHARACTER SET utf8mb4 DEFAULT NULL COMMENT 'Period Cover of Payment',
                    $table->integer('total_health');  //`total_health` int(11) NOT NULL,
                    $table->integer('educ_3_5');  //`educ_3_5` int(10) NOT NULL COMMENT 'age 3-5 education',
                    $table->integer('educ_6_14');  //`educ_6_14` int(10) NOT NULL COMMENT 'age 6-14 education',
                    $table->integer('educ_hs_6_14');   //`educ_hs_6_14` int(10) NOT NULL,
                    $table->integer('educ_elem_15_18');  //`educ_elem_15_18` int(10) NOT NULL,
                    $table->integer('educ_hs_15_18');  //`educ_hs_15_18` int(10) NOT NULL,
                    $table->integer('educ_15_17');  //`educ_15_17` int(10) NOT NULL,
                    $table->integer('tot_educ');  //`tot_educ` int(10) NOT NULL COMMENT 'Total of Children(Education)',
                    $table->integer('grand_tot');  //`grand_tot` int(10) NOT NULL COMMENT 'Grand Total ',
                    $table->string('paid',15);  //`paid` varchar(15) CHARACTER SET utf8 NOT NULL COMMENT 'If Paid (Yes/No)',
                    $table->string('regular_special_etc',25);  //`regular_special_etc` varchar(25) CHARACTER SET utf8 NOT NULL DEFAULT 'Regular' COMMENT 'Regular/special OTC',
                    $table->string('remarks',25);  //`remarks` varchar(25) CHARACTER SET utf8 NOT NULL COMMENT 'Remarks',
                    $table->string('standard_remarks',50);  //`standard_remarks` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'Details of not paid ',
                    $table->string('specific_remarks',100)->nullable();  //`specific_remarks` varchar(100) DEFAULT NULL,
                    $table->string('op_no',15);  //`op_no` varchar(15) CHARACTER SET utf8 NOT NULL,
                    $table->date('date');  //`date` varchar(15) CHARACTER SET utf8 NOT NULL COMMENT 'Date Realese',
                    $table->integer('year');  //`year` varchar(10) CHARACTER SET utf8 NOT NULL,
                    $table->string('mop',50);  //`mop` varchar(50) CHARACTER SET utf8 NOT NULL,
                    $table->string('lbp_branch',50);  //`lbp_branch` varchar(50) CHARACTER SET utf8 NOT NULL COMMENT 'Landbank Branch',
                    $table->integer('amount');  //`amount` int(15) NOT NULL COMMENT 'Amount Paid',
                    $table->string('set_no',25);  //`set_no` varchar(25) CHARACTER SET utf8 NOT NULL,
                    $table->string('username',20);  //`username` varchar(20) CHARACTER SET utf8 NOT NULL COMMENT 'Foreign Key From user table',
                    $table->string('type_of_program',25);  //`type_of_program` varchar(25) CHARACTER SET utf8 NOT NULL,
                    $table->string('type_registration',50);  //`type_registration` varchar(50) CHARACTER SET utf8 NOT NULL,
                    $table->string('type_payrol',50);  //`type_payrol` varchar(50) CHARACTER SET utf8 NOT NULL,
                    $table->string('date_process',15);  //`date_process` varchar(15) CHARACTER SET utf8 NOT NULL,
                    $table->tinyInteger('cnt_6_14'); //`cnt_6_14` tinyint(3) NOT NULL,
                    $table->tinyInteger('cnt_3_5');  //`cnt_3_5` tinyint(3) NOT NULL,
                    $table->tinyInteger('cnt_15_17');  //`cnt_15_17` tinyint(3) NOT NULL,
                    $table->string('particular',15);  //`particular` varchar(15) CHARACTER SET utf8 NOT NULL,
                    $table->string('napa_control_no',50);  //`napa_control_no` varchar(50) CHARACTER SET utf8 NOT NULL,
                    $table->string('psgc_code',25)->nullable();  //`psgc_code` varchar(25) DEFAULT NULL,
                    $table->integer('upload_id');  //`upload_id` int(11) NOT NULL,
                    $table->double('rice',11,2);  //`rice` double(11,2) NOT NULL,
            });
            //DB::table('filters')->insert([]);
            $filters = new \App\Filters();
            $filters->year = $year;
            $filters->modepayment = '';
            $filters->period_cover = '';
            $filters->bank = '';
            $filters->set = '';
            $filters->program = '';
            $filters->final_remarks = '';
            $filters->registration = '';
            $filters->save();
            return response()->json(['flag' => 1,'msg' => 'Well Done. New database storage for the year '.$year.' created!.']);
        }else{
            return response()->json(['flag' => 0,'msg' => 'Database storage already exist for the year '.$year]);
        }        
    }
    public function rebuildfilter(Request $request){        
        $year = $request->year;         
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
        return response()->json(['flag' => 1,'msg' => 'Well Done. Successfully filter rebuild']);
    }
}
