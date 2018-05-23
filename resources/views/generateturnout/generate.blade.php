@php
use Illuminate\Support\Facades\Session; 
@endphp

<html>
       <script type="text/javascript">
            //var speed = 9999999;
            var speed = 9999;
            var timer = null;
            var currentpos = 0, alt = 1, curpos1 = 1, curpos2 = -1;
            function initialize() {
                startit();
            }
            function scrollwindow() {
                if (document.all && !document.getElementById) temp = document.body.scrollTop;
                else temp = window.pageYOffset;
                if (alt == 0) alt = 2;
                else alt = 1;
                if (alt == 0) curpos1 = temp;
                else curpos2 = temp;
                if (curpos1 != curpos2) {
                    if (document.all) currentpos = document.body.scrollTop + speed;
                    else currentpos = window.pageYOffset + speed;
                    window.scroll(0, currentpos);
                }
                else {
                    currentpos = 0;
                    window.scroll(0, currentpos);
                }
            }
            function startit() { timer = setInterval("scrollwindow()", 100); }
            function stopInterval() { clearInterval(timer); }
            window.onload = initialize();
        </script>   
        <script type="text/javascript">
        function moveprogress(w){
            var elem = document.getElementById("textbar");   
            elem.style.width = w + '%';
            document.getElementById('textbar').innerHTML = "("+w+"%)";                                
        }
        
        </script>
        
        <style>
                .progress { margin-top:-3px;margin-left: -4px; position: fixed; width: 99%}
                .progress .bar { background: #eee; -moz-border-radius: 50px; -webkit-border-radius: 50px; border-radius: 50px; padding: 1px; border: 1px solid #ccc; }
                .progress .bar { -moz-box-shadow: inset 2px 2px 3px #fff; -webkit-box-shadow: inset 2px 2px 3px #fff; box-shadow: inset 2px 2px 3px #fff; }
                .progress .bar .value { height: 5px; -moz-border-radius: 50px; -webkit-border-radius: 50px; border-radius: 50px; background-image: url(../images/progress.png); }

                .progress .bar2 { background: #eee; -moz-border-radius: 2px; -webkit-border-radius: 2px; border-radius: 2px; padding: 1px; border: 1px solid #ccc; }
                .progress .bar2 { -moz-box-shadow: inset 2px 2px 3px #fff; -webkit-box-shadow: inset 2px 2px 3px #fff; box-shadow: inset 2px 2px 3px #fff; }
                .progress .bar2 .value { padding: 0; text-align: center; -moz-border-radius: 2px; -webkit-border-radius: 2px; border-radius: 2px; color: #fff;width: 30px; }
                .progress .bar2 .value { background-image: url(../images/progress.png); background-position: 0 0; font-size: 11px; font-weight: bold;font-family: Arial, Helvetica, sans-serif }

                .progress .bluebar { background-color: #06f; box-shadow: inset 1px 1px 2px #9af; }
                .progress .orangebar { background-color: #F90; }
                .progress .redbar { background-color: #cc0000; }    
                .progress .greenbar { background-color: #578c63; box-shadow: inset 1px 1px 2px #9af;}    
                
               table {
                   border-collapse: collapse;
               }

               table, th, td {
                   border: 0px solid #ddd;
               }  
               td {
                   font-family: Arial, Helvetica, sans-serif;
                   color: #666;
                   font-size: 12px;
               }
               tr:hover {background-color: #ffe9ad;} 

           </style>
<body> 
<div style="margin-top: -10px;margin-left: -4px;position:fixed;width: 99%;height:15px;background-color: #ffffff;z-index: 0"></div>
<div class="progress">
    <div class="bar2" id="bar2"><div id="textbar" class="value greenbar" style="width: 1%;">(1%)</div></div>
</div>    
<table border='0' cellpadding='0' cellspacing='2'>
@php

\ini_set("memory_limit",-1);
\ini_set('max_execution_time', 0);
$regionList = Array('NCR','CAR','I','II','III','IV-A','IV-B','V','VI','VII','VIII','IX','X','XI','XII','CARAGA','ARMM','NIR');
$category = Array('Education - Children 3 to 5 years old','Education - Children 6 to 14 years old','Education - Children 15 -18 years old','Health Center Visits - Pregnant women','Health Center Visits - Children 0 to 5 years old','Family Development Sessions','Deworming','Health Center Visits - Children 0 to 5 years old or Pregnant women','Education - Children 3 to 18 years old');
$setAndGroup = Array('1','2','3A','3B','3C','3D','4A','4B','4C','4D','5A','5B','6A','6B','6C','6D','6E','7A','7B','7C','7E','8A','8B','8C','ALL');
if (ob_get_level() == 0) ob_start();
ob_flush();
flush();
$cnt = 0; $error = 0; $success = 0;
foreach($regionList as $reg):
$cntregion = 0; $errorfound = 0;
    foreach($category as $cat):
    if (ob_get_level() == 0) ob_start();
            foreach ($setAndGroup as $set):
                $month_covered = explode('-',$months);
                $str = substr($set,0,1);
                $str1 = (int)$str;
                $str2 = substr($set,1,1);
                $where = [['set','=',$str1],['category','=',$cat],['month','=',$month_covered[0]],['region','=',$reg]];
                $where2 = [['set','=',$str1],['category','=',$cat],['month','=',$month_covered[1]],['region','=',$reg]];
                if($str1 > 2) { $where[] = ['setgroup','=',$str2]; $where2[] = ['setgroup','=',$str2]; }
                if($set=='ALL'){ unset($where[0]); unset($where[4]); unset($where2[0]); unset($where2[4]); }
                if($cat == "Deworming"){ $where[2][2] = $month_covered[1]; }
                $results = DB::table('tbl_turnout_2017_1')
                     ->select('region', 
                                DB::raw('SUM(`eligibility`) as `eligible`'),
                                DB::raw('SUM(`not_attend_dominant`) as `not_attend`'),
                                DB::raw('SUM(`attend_dominant`) as `attended`'),
                                DB::raw('SUM(`attend_del_dominant`) as `deleted`'),
                                DB::raw('SUM(`monitored_dominant`) as `mon_dominant`'),
                                DB::raw('SUM(`not_encoded_approved`) as `not_enc_app`'),
                                DB::raw('SUM(`encoded_under_forcem`) as `enc_as_fm`'),
                                DB::raw('SUM(`encoded_approved`) as `enc_approve`'),
                                DB::raw('SUM(`submitted_deworming`) as `submitted_deworming`'),
                                DB::raw('SUM(`non_compliant`) as `non_comp`'),
                                DB::raw('SUM(`compliant`) as `comp`'),
                                DB::raw('IF(SUM(`encoded_approved`)=0,"0.00",ROUND((SUM(`compliant`)/SUM(`encoded_approved`))*100,2)) as `compliant_vs_submitted`'),
                                DB::raw('IF((SUM(`compliant`)+SUM(`encoded_approved`))=0,"0.00", ROUND(((SUM(`compliant`)+SUM(`encoded_under_forcem`))/SUM(`eligibility`))*100,2)) as `comp_plus_calamity_vs_eligible`')
                             )
                ->where($where)
                ->groupBy('region')->get();
                $results2 = DB::table('tbl_turnout_2017_1')
                     ->select(
                                DB::raw('SUM(`non_compliant`) as `non_comp2`'),
                                DB::raw('SUM(`compliant`) as `comp2`'),
                                DB::raw('SUM(`submitted_deworming`) as `submitted_deworming`'),
                                DB::raw('IF(SUM(`encoded_approved`)=0,"0.00",ROUND((SUM(`compliant`)/SUM(`encoded_approved`))*100,2)) as `compliant_vs_submitted2`'),
                                DB::raw('IF((SUM(`compliant`)+SUM(`encoded_approved`))=0,"0.00", ROUND(((SUM(`compliant`)+SUM(`encoded_under_forcem`))/SUM(`eligibility`))*100,2)) as `comp_plus_calamity_vs_eligible2`')
                             )
                ->where($where2)
                ->groupBy('region')->get();
                     if(count($results) > 0){
                        $ave_comp_submitted = (($results[0]->enc_approve==0) ? "0.00":  round(($results[0]->compliant_vs_submitted + $results2[0]->compliant_vs_submitted2)/2,2));
                        $ave_comp_calamity = round(($results[0]->comp_plus_calamity_vs_eligible + $results2[0]->comp_plus_calamity_vs_eligible2)/2,2);
                        $deworming_compliant_vs_submitted = (($results2[0]->submitted_deworming <=0)? 0 : round((($results2[0]->comp2 / $results2[0]->submitted_deworming) * 100),2));
                        $arrayTurnout = Array(
                                ['region' => $results[0]->region,
                                'region' => $results[0]->region,
                                'eligible' => $results[0]->eligible,
                                'not_attending_sch_hc' => $results[0]->not_attend,
                                'attending_sch_hc' => $results[0]->attended,
                                'attending_deleted_sch_hc' => $results[0]->deleted,
                                'enrolled_within_municipality' => $results[0]->mon_dominant,
                                'not_submitted' => $results[0]->not_enc_app,
                                'state_of_calamity' => $results[0]->enc_as_fm,
                                'submitted' => $results[0]->enc_approve,
                                'submitted_deworming' => $results[0]->submitted_deworming,
                                'non_compliant1' => $results[0]->non_comp,
                                'compliant_w_cash_grant1' => $results[0]->comp,
                                'compliant_vs_submitted1' => $results[0]->compliant_vs_submitted,
                                'compliant_calamity_vs_eligible1' => $results[0]->comp_plus_calamity_vs_eligible,
                                'non_compliant2' => $results2[0]->non_comp2,
                                'compliant_w_cash_grant2' => $results2[0]->comp2,
                                'compliant_vs_submitted2' => (($cat =="Deworming") ? $deworming_compliant_vs_submitted : $results2[0]->compliant_vs_submitted2),
                                'compliant_calamity_vs_eligible2' => $results2[0]->comp_plus_calamity_vs_eligible2,
                                'ave_comp_rate_comp_vs_submitted' => $ave_comp_submitted,
                                'ave_comp_rate_comp_calamity_vs_eligible' => $ave_comp_calamity,
                                'category' => $cat,
                                'category_description' => $cat,
                                'sheet_name' => $cat,
                                'year' => $year,
                                'period' => $period,
                                'set' => $str1,
                                'set_group' => $str2,
                                'generated_by' => Auth::user()->username,
                                'date_generated' => DB::raw('now()')]
                            ); 
                            $total_count = round(($cnt / 4050) * 100);
                            $total_count_region = round(($cntregion / 225) * 100);
                            try{
                                echo "<script>moveprogress(".$total_count.");</script>";
                                DB::table('tbl_turnout')->insert($arrayTurnout);
                                echo '<tr>
                                        <td>Region</td>
                                        <td width="65">: '.$results[0]->region.'</td>
                                        <td>Year</td>
                                        <td width="40">: '.$year.'</td>
                                        <td>Period</td>
                                        <td width="20">: '.$period.'</td>
                                        <td>Set</td>
                                        <td width="30">: '.$str1.$str2.'</td>
                                        <td>Category</td>
                                        <td>: '.substr($cat,0,15).'...</td>
                                        <td>Eligible :</td>
                                        <td align="right" width="40"> '.number_format($results[0]->eligible).'</td>
                                        <td align="center" width="70">Attended :</td>
                                        <td align="right" width="40"> '.number_format($results[0]->attended).'</td>
                                        <td align="center" width="50">Msg :</td>
                                        <td align="right" width="40"><font color=green>OK!</font></td>
                                </tr>';
                                $success++;
                                
                                echo "<script>parent.regionProgress(".$total_count_region.",'".$reg."');</script>";
                            }catch(\Illuminate\Database\QueryException $ex){
                                echo '<tr>
                                        <td colspan="16"><font color=red>'.substr($ex->getMessage(),0,130).'...</font></td>
                                      </tr>';
                                $error++;$errorfound++;
                                echo "<script>parent.regionProgressError(".$errorfound.",'".$reg."');</script>";                                                                      
                            }
                    }
                    $cnt++;
                    $cntregion++;
                    ob_flush();
                    flush();
            endforeach;
            ob_end_flush();
        endforeach;
     endforeach;   
        echo "</table>";        
        echo "<br><font style='font-family: Arial, Helvetica, sans-serif;' size=2><b>Generation finished!</b></font><br><br>";
        echo "<table border='1' cellpadding='2' cellspacing='2'>";
            echo "<tr>
                <td align=left width=140>Year :</td>
                <td width=50 align=left><font><b>".$year."</b></font></td>
                <td></td>
            </tr>            
            <tr>
                <td align=left width=140>Period :</td>
                <td width=50 align=left><font><b>".$period."</b></font></td>
                <td></td>
            </tr>            
            <tr>
                <td align=left width=140>Generated successful :</td>
                <td width=50 align=let><font color=green><b>".number_format($success)."</b></font></td>
                <td>record(s)</td>
            </tr>
            <tr>
                <td align=left width=140>Errors Encountered :</td>
                <td width=50 align=left><font color=red><b>".number_format($error)."</b></font></td>
                <td>record(s)</td>
            </tr>";
        echo "</table>";
        
echo '<script type="text/javascript">window.scrollTo(0,document.body.scrollHeight); stopInterval();</script>';                
@endphp

</body>
</html>