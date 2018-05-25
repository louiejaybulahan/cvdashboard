<?php
namespace App\Http\Controllers;

use Auth;
use Schema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tbl_turnout;
use Config,Eloquent;
use Excel;
class SiteController extends Controller {

    public function __construct() {
        // $this->middleware('auth');
    }

    public function index() {      

		$category = DB::table('tbl_turnout')->select(DB::raw('category'))->groupBy('category')->get();	
		$regions = DB::table('tbl_turnout')->select('region')->groupBy('region')->get();	
		$yearperiod = DB::table('tbl_turnout')->select(DB::raw('year,period'))->groupBy('year','period')->get();	
		$year = DB::table('tbl_turnout')->select(DB::raw('year'))->groupBy('year')->get();	
        return view('site.site',['category' => $category,
								  'yearperiod' =>$yearperiod,
								  'year' =>$year,
								  'regions' =>$regions]);               
    }
	
	public function dashboarddata(Request $request){
		$year = $request->input('year');
		$period = $request->input('period');
		$regions = $request->input('regions');
		$category = $request->input('category');
		$hhset = $request->input('hhset');
		$ccsedata['dataprovider1'] = null;
		$ccsedata['dataprovider2'] = null;
		//$cvturnout = null;
		//$dashdata = [];
		$filtered_category = array("Family Development Sessions","Deworming");
		$month = DB::table('lib_periods')->select('months')->where([['year','=',trim($year)],['period','=',trim($period)]])->first();
		$monthnum = explode("-", $month->months);
		$monthName1 = date("F", mktime(0, 0, 0, $monthnum[0], 10));
		$monthName2 = date("F", mktime(0, 0, 0, $monthnum[1], 10));
		$report_type = $request->input('report');
		
		//if($report_type == 1){			        
			//	$dashdata = $this->turnoutquery($regions,$category,$hhset,$year,$period);									
			//} 
		
		if($report_type == 2){			
				$dashdata = $this->turnoutquery($regions,$category,$hhset,$year,$period);	
			    $dashdata1 = $this->ccse_query($regions,$category,$hhset,$year,$period);	
				foreach($dashdata1 as $q){					
		$ccsedata['dataprovider1'].= '{"region" : "' .  $q->region . '","compliant_vs_submitted":' . intval($q->compliant_vs_submitted1) . ',"compliant_calamity_vs_eligible": ' .  intval($q->compliant_calamity_vs_eligible1) . '},';
		$ccsedata['dataprovider2'].= '{"region" : "' .  $q->region . '","compliant_vs_submitted":' . intval($q->compliant_vs_submitted2) . ',"compliant_calamity_vs_eligible": ' .  intval($q->compliant_calamity_vs_eligible2) . '},';
					//$ccsedata['dataprovider1'][] = ["region" => $q->region,'compliant_vs_submitted' => intval($q->compliant_vs_submitted1),'compliant_calamity_vs_eligible' =>  intval($q->compliant_calamity_vs_eligible1)];	
					//$ccsedata['dataprovider2'][] = ['region' => $q->region,'compliant_vs_submitted' => intval($q->compliant_vs_submitted2),'compliant_calamity_vs_eligible' =>  intval($q->compliant_calamity_vs_eligible2)];	
				}	
		}
		
		$ccsedata['dataprovider1'] = '[' . rtrim($ccsedata['dataprovider1'], ',') . ']';
		$ccsedata['dataprovider2'] = '[' . rtrim($ccsedata['dataprovider2'], ',') . ']';
		//echo "<pre>";
		//print_r($ccsedata);
		//echo "</pre>";
	//	$ccsedata['dataprovider1'] = array_map('stripslashesFull',$ccsedata['dataprovider1']);
		
		
		return response()->json(['cvturnout' =>  $dashdata->toArray(),'ccsedata' => $ccsedata, '_token' => csrf_token(), 'month1' => $monthName1, 'month2' => $monthName2, 'report_type' => $report_type]);		
	}
	
	
	public function exportexcel(Request $request){
		$year = $request->year;
		$period = $request->period;
		$category = $request->category;
		$hhset = $request->hhset;
		$regions[] = explode(',',$request->regions);	
		$cvturnout_columns = [];
		$filtered_category = array("Family Development Sessions","Deworming");
			if(in_array($category,$filtered_category)){
				if($category == trim('Deworming')){
					$cvturnout_columns[] = ['Region','Eligible for CVS Education Monitoring','Not Attending School','Attending School',
											'Attending Deleted School',	'Enrolled Within Municipality',	'Not Submitted(Monitored, no cash grant)',
											'State of Calamity', 'CVS Submitted (no deworming conducted, monitored with cash grant)',
											'CVS Submitted (conducted deworming , monitored)', 'Non Compliant (monitored, with cash grant)',
											'Compliant (monitored, with cash grant)','Compliant vs Submitted(Conducted Deworming)'];
											
						$turnoutquery= $this->turnoutquery_deworming($category,$hhset,$year,$period);
										foreach($turnoutquery->toArray() as $r){			
												$cvturnout_columns[] = $r;
										}
					$cvturnout_columns = json_decode( json_encode($cvturnout_columns), true);					
				}
				if($category == trim('Family Development Sessions')){
					$cvturnout_columns[] = [];
				}
			}else{
					$cvturnout_columns[] =  Schema::getColumnListing('tbl_turnout');//[1,2,3,4,5]			
						$turnoutquery= $this->turnoutquery($regions[0],$category,$hhset,$year,$period);
										foreach($turnoutquery->toArray() as $r){			
												$cvturnout_columns[] = $r;
										}
					$cvturnout_columns = json_decode( json_encode($cvturnout_columns), true);
			}
			
		Excel::create('CV_turnout', function($excel) use ($cvturnout_columns) {		 
						$excel->setTitle('CV_turnout');
						$excel->setCreator('WSC')->setCompany('DSWD_CVD');
						$excel->setDescription('CV turn-outs');        
						$excel->sheet('sheet1', function($sheet) use ($cvturnout_columns) {
										$sheet->fromArray($cvturnout_columns, null, 'A1', false, false);
								});
		})->download('xlsx'); 	
	}
	
	public function turnoutquery($regions,$category,$hhset,$year,$period){	
		$whereclause = [['category','=',trim($category)],['set','=',trim($hhset)],['year','=',trim($year)],['period','=',trim($period)]];
		 if($regions <> 'null'){
		 $tquery = DB::table('tbl_turnout')->where($whereclause)->whereIn('region',$regions)->get();
		 } else{
			 $tquery = DB::table('tbl_turnout')->where($whereclause)->get(); 
		 }
		return $tquery;
	}
	
	public function turnoutquery_deworming($category,$hhset,$year,$period){
		$whereclause = [['category','=',trim($category)],['set','=',trim($hhset)],['year','=',trim($year)],['period','=',trim($period)]];
		return DB::table('tbl_turnout')->select(DB::raw('region,eligible,not_attending_sch_hc,attending_sch_hc,attending_deleted_sch_hc,enrolled_within_municipality,not_submitted,state_of_calamity,compliant_w_cash_grant2,compliant_vs_submitted2,compliant_calamity_vs_eligible2,ave_comp_rate_comp_vs_submitted,ave_comp_rate_comp_calamity_vs_eligible'))
										->where($whereclause)->get();
	}
	

		
	public function ccse_query($regions,$category,$hhset,$year,$period){
			
		$whereclause = [['category','=',trim($category)],['set','=',trim($hhset)],['year','=',trim($year)],['period','=',trim($period)]];
		 if($regions <> 'null'){
		 $tquery = DB::table('tbl_turnout')->select(DB::raw('region,compliant_vs_submitted1,compliant_calamity_vs_eligible1,compliant_vs_submitted2,compliant_calamity_vs_eligible2'))->where($whereclause)->whereIn('region',$regions)->get();
		 } else{
			 $tquery = DB::table('tbl_turnout')->select(DB::raw('region,compliant_vs_submitted1,compliant_calamity_vs_eligible1,compliant_vs_submitted2,compliant_calamity_vs_eligible2'))->where($whereclause)->get(); 
		 }
		return $tquery;
	}	
}








































