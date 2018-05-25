@extends('layouts.main')

@section('title', 'Dashboard')
@section('optLayout','noright')

@section('cssExtention')
<!-- link rel="stylesheet" href="js/datatables/datatables.min.css" type="text/css" media="screen" / -->
<link rel="stylesheet" href="js/amcharts_weezam/export.css" type="text/css" media="all" />
<link rel="stylesheet" href="js/amcharts_weezam/bodycharts.css" type="text/css" media="all" />
<link rel="stylesheet" href="js/chosen/chosen.css" type="text/css" media="screen" />
@endsection

@section('jsExtention')

<script type="text/javascript" src="{{ asset('js/plugins/jquery.jgrowl.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/amcharts_weezam/amcharts.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/amcharts_weezam/serial.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/amcharts_weezam/export.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/amcharts_weezam/light.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/amcharts_weezam/amcharts_sample.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/chosen/chosen.jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/chosen/init.js') }}"></script>
<script type="text/javascript">
 jQuery('#loading').hide();
	jQuery(document).ready(function () {
		jQuery('#contentid').hide();
	    jQuery('#exportbtn').hide();
		jQuery('.chosen').chosen({ width: "65%",height: "35%"});
				   
	});	
	function jsMessage(message) { jQuery.jGrowl(message); return false; }
	jQuery('#submitfilter').click(function(){ jsloaddashboarddata(); });				
	function jsdatarequest(){
					return {
						 '_token':'{{ csrf_token() }}',  
						 'regions' : jQuery('#regions').val(),
						 'report' : jQuery('#report').val(),
						 'category' : jQuery('#category').val(),
						 'hhset' : jQuery('#hhset').val(),
						 'year' : jQuery('#year').val(),
						 'period' : jQuery('#period').val(),
						 };
				}
				
	function jsloaddashboarddata(){		 
		var category =  jQuery('#category').val();
		var hhset = jQuery('#hhset').val();
		var year = jQuery('#year').val();
		var period = jQuery('#period').val();
		var  regions = jQuery('#regions').val();
		var base = '{!! route('site.exportexcel') !!}';
		var url = base+'?category='+category+'&hhset='+hhset+'&year='+year+'&period='+period +'&regions='+regions;
		jQuery('#exportbtn').attr('href',url);
		var param = jsdatarequest();          
				jQuery('#loading').show();
				jQuery.ajax({
							type: "POST", url:'{{ route('site.dashboarddata') }}', data: param, dataType: 'json', cache: false,        
							error: function (request, status, error) { jsMessage('Error Request'); },
							success: function (data) {   
							jQuery('#contentid').show();
							var vardata = jQuery('#hhset :selected').text();	
						   jQuery("#header_result").text( ((vardata != 0) ? vardata : 'ALL SETS' ) +' '+ jQuery('#report :selected').text() + ' FOR ' + jQuery('#category :selected').text() + ' IN THE YEAR ' + jQuery('#year :selected').text()+ ' PERIOD ' + jQuery('#period :selected').text());
						   
						   if(category == jQuery.trim("Deworming")){
							    jQuery("#dashresult").html('<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="cvturnout_table">' 
														  +'<thead>'															
															+'<tr>'
																+'<th class="head2">Region</th>'
																+'<th class="head2">Eligible for CVS Education Monitoring</th>'
																+'<th class="head2">Not Attending School</th>'
																+'<th class="head2">Attending School</th>'
																+'<th class="head2">Attending Deleted School</th>'
																+'<th class="head2">Enrolled Within Municipality </th>'
																+'<th class="head2">Not Submitted(Monitored, no cash grant) </th>'
																+'<th class="head2">State of Calamity </th>'
																+'<th class="head2">CVS Submitted (no deworming conducted, monitored with cash grant) </th>'
                                                                +'<th class="head2">CVS Submitted (conducted deworming , monitored) </th>'																												
																+'<th class="head2">Non Compliant (monitored, with cash grant) </th>'
																+'<th class="head2">Compliant (monitored, with cash grant) </th>'
																+'<th class="head1">Compliant vs Submitted(Conducted Deworming) </th>'
															+'</tr>'
														  +'</thead>'
														  +'<tbody id="tbodyclear">' 
														  +'</tbody>'
														+'</table>');								
								 $("#tbodyclear").empty();
								jQuery.each(data.cvturnout, function(key, val){ 
								 
									 $('#cvturnout_table').append('<tr>'
																		+'<td>'+ val.region+ '</td>'
																		+'<td>'+ val.eligible.toLocaleString()+ '</td>'
																		+'<td>'+ val.not_attending_sch_hc.toLocaleString()+ '</td>'
																		+'<td>'+ val.attending_sch_hc.toLocaleString()+ '</td>'
																		+'<td>'+ val.attending_deleted_sch_hc.toLocaleString()+ '</td>'
																		+'<td>'+ val.enrolled_within_municipality.toLocaleString()+ '</td>'
																		+'<td>'+ val.not_submitted.toLocaleString()+ '</td>'
																		+'<td>'+ val.state_of_calamity.toLocaleString()+ '</td>'
																		+'<td>'+ val.compliant_w_cash_grant2.toLocaleString()+ '</td>'
																		+'<td>'+ val.compliant_vs_submitted2.toLocaleString()+ '%</td>'
																		+'<td>'+ val.compliant_calamity_vs_eligible2.toLocaleString()+ '%</td>'
																		+'<td>'+ val.ave_comp_rate_comp_vs_submitted.toLocaleString()+ '%</td>'
																		+'<td>'+ val.ave_comp_rate_comp_calamity_vs_eligible.toLocaleString()+ '%</td>'		
																  +'</tr>');		
																								   })
						   }else{
						   
						   jQuery("#dashresult").html('<table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="cvturnout_table">' 
														 +'<colgroup>'
														 +'<col class="con0">'
														 +'<col class="con0">'
														 +'<col class="con0">'
														 +'<col class="con0">'
														 +'<col class="con0">'
														 +'<col class="con0">'
														 +'<col class="con0">'
														 +'<col class="con0">'
														 +'<col class="con0">'
														 +'<col class="con0">'
														 +'<col class="con0">'
														 +'<col class="con1">'
														 +'<col class="con1">'
														 +'<col class="con0">'
														 +'<col class="con0">'
														  +'<col class="con1">'
														 +'<col class="con1">'
														  +'<col class="con1">'
														 +'<col class="con1">'
														 +'</colgroup>'
														  +'<thead>'
															
															+'<th colspan="9" class="head0">'
															+'<th colspan="4" class="head2">'+ data.month1 +'</th>'
															+'<th colspan="4" class="head2">'+ data.month2 +'</th>'
															+'<th colspan="2" class="head0"></th>'
															
															+'<tr>'
																+'<th class="head2">Region</th>'
																+'<th class="head2">Eligible for CVS Education Monitoring</th>'
																+'<th class="head2">Not Attending School</th>'
																+'<th class="head2">Attending School</th>'
																+'<th class="head2">Attending Deleted School</th>'
																+'<th class="head2">Enrolled Within Municipality </th>'
																+'<th class="head2">Not Submitted </th>'
																+'<th class="head2">State of Calamity </th>'
																+'<th class="head2">Submitted </th>'
																+'<th class="head2">Non   Compliant </th>'
																+'<th class="head2">Compliant (monitored, with cash grant) </th>'
																+'<th class="head1">Compliant vs Submitted </th>'
																+'<th class="head1">Compliant+State of Calamity vs Eligible</th>'
																+'<th class="head2">Non   Compliant </th>'
																+'<th class="head2">Compliant (monitored, with cash grant) </th>'
																+'<th class="head1">Compliant vs Submitted </th>'
																+'<th class="head1">Compliant+State of Calamity vs Eligible</th>'
																+'<th class="head1">Average Compliance Rate (Compliant vs Submitted) </th>'
																+'<th class="head1">Average Compliance Rate (Compliant+State of Calamity vs Eligible) </th>'
																
																
															+'</tr>'
														  +'</thead>'
														  +'<tbody id="tbodyclear">' 
														  +'</tbody>'
														+'</table>');								
								 $("#tbodyclear").empty();
								jQuery.each(data.cvturnout, function(key, val){ 
								 
									 $('#cvturnout_table').append('<tr>'
																		+'<td>'+ val.region+ '</td>'
																		+'<td>'+ val.eligible.toLocaleString()+ '</td>'
																		+'<td>'+ val.not_attending_sch_hc.toLocaleString()+ '</td>'
																		+'<td>'+ val.attending_sch_hc.toLocaleString()+ '</td>'
																		+'<td>'+ val.attending_deleted_sch_hc.toLocaleString()+ '</td>'
																		+'<td>'+ val.enrolled_within_municipality.toLocaleString()+ '</td>'
																		+'<td>'+ val.not_submitted.toLocaleString()+ '</td>'
																		+'<td>'+ val.state_of_calamity.toLocaleString()+ '</td>'
																		+'<td>'+ val.submitted.toLocaleString()+ '</td>'
																		+'<td>'+ val.non_compliant1.toLocaleString()+ '</td>'
																		+'<td>'+ val.compliant_w_cash_grant1.toLocaleString()+ '</td>'
																		+'<td>'+ val.compliant_vs_submitted1+ '%</td>'
																		+'<td>'+ val.compliant_calamity_vs_eligible1+ '%</td>'
																		+'<td>'+ val.non_compliant2.toLocaleString()+ '</td>'
																		+'<td>'+ val.compliant_w_cash_grant2.toLocaleString()+ '</td>'
																		+'<td>'+ val.compliant_vs_submitted2.toLocaleString()+ '%</td>'
																		+'<td>'+ val.compliant_calamity_vs_eligible2.toLocaleString()+ '%</td>'
																		+'<td>'+ val.ave_comp_rate_comp_vs_submitted.toLocaleString()+ '%</td>'
																		+'<td>'+ val.ave_comp_rate_comp_calamity_vs_eligible.toLocaleString()+ '%</td>'
																		
																		
																  +'</tr>');		
								})
					
						  }						
							}
								}).done(function(){ 							
										jQuery('#loading').fadeOut();
										 jQuery('#exportbtn').show();
								});   
								return false;					
					}

</script>

@endsection

@section('content')		

<div class="one_half">
                	<div class="title"><h2 class="chart"><span>Filter Options</span></h2></div>
					
				
                   <form id="form2" class="stdform stdform2" method="post" action="">
                    	<p>
                        	<label>Regions</label>	
							<span class="field">
									<select name="regions" id="regions" class="chosen" multiple="multiple">									
									@foreach($regions AS $r)
									<option value="{{ $r->region }}">{{ $r->region }}</option>
									@endforeach	
									</select><small class="desc" style="margin:0px;">(*) leaving it blank will select all Regions by default</small>
											<small class="desc" style="margin:0px;">(*) It will compare data by selecting 2 or more regions</small>
						 	</span>	
							 
                          </p> 
                        <p>
                        	<label>Turn-out Reports</label>	
							<span class="field">
									<select name="report" id="report" >									
									<option value="1">CV Turn-out</option>
									<option value="2">Compliant Comparison vs Submitted vs Eligible </option>
									<option value="3">Eligible for CVS Monitoring</option>
									<option value="4">Attending and Not Attending in School beneficiaries </option>
									<option value="5">Paralleling Eligible for CVS Monitoring and Non-Compliants</option>									
									</select>													
							</span>	
                          </p> 
                        <p>
                        	<label> Category  & Sets</label>	
							<span class="field">
									<select name="category" id="category" >
									@foreach($category AS $r)
									<option value="{{ $r->category }}">{{ $r->category }}</option>
									@endforeach
                            </select> <br><br>
							<select name="selection" id="hhset">
									<option value="0">All sets</option>
									<option value="1">Set 1</option>
									<option value="2">Set 2</option>
									<option value="3">Set 3</option>
									<option value="4">Set 4</option>
									<option value="5">Set 5</option>
									<option value="6">Set 6</option>
									<option value="7">Set 7</option>
									<option value="8">Set 8</option>
                            </select>
							<select name="selection" id="hhsetgroup">
									<option value="0">select set groups</option>
									<option value="a">A</option>
									<option value="b">B</option>
									<option value="c">C</option>
									<option value="d">D</option>
									<option value="e">E</option>
									
                            </select>
							</span>	
                          </p>  
							<p>
                        	<label> Year & Period </label>	
							<span class="field">
									<select name="selection" id="year">
									@foreach($year AS $r)
									<option value="{{ $r->year }}">{{ $r->year }}</option>
									@endforeach
                            </select>
							<select name="selection" id="period">
									@foreach($yearperiod AS $r)
									<option class ="{{ $r->year }}" value="{{ $r->period }}">{{ $r->period }}</option>
									@endforeach
                            </select>						
							</span>	
                          </p> 					  					  
                        	<p class="stdformbutton">
                        	<a href="#dashdata" id="submitfilter" class="stdbtn btn_blue"><span>Search</span></a>   
                            <input type="reset" class="reset radius2" value="Reset Filters">
							  &nbsp;&nbsp;&nbsp;<img src="{{ asset('images/loading.gif') }}" id="loading"></img>
                        </p>
						</form>
                </div>
	<br clear="all">
	<br>
	<div class="content">
              
				<div id="contentid">
					<div class="contenttitle radiusbottom0" >
						<h2 class="table"><span id ="header_result"></span></th></h2>
					</div><!--contenttitle-->	
					<div id="dashresult"></div>	<br>
					<div id="dashexport"><a href="#" id="exportbtn" class="btn btn2 btn_book" style="background-color: rgb(247, 247, 247);"><span>Export Result to Excel(.xlsx)</span></a></div>					             
                 </div>
				 <br clear="all">    
	</div>
<br clear="all">	
@endsection

@section('right')

@endsection
