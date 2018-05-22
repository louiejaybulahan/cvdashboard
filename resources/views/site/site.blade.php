@extends('layouts.main')

@section('title', 'Dashboard')
@section('optLayout','noright')

@section('cssExtention')
<!-- link rel="stylesheet" href="js/datatables/datatables.min.css" type="text/css" media="screen" / -->
<link rel="stylesheet" href="js/amcharts_weezam/export.css" type="text/css" media="all" />
<link rel="stylesheet" href="js/amcharts_weezam/bodycharts.css" type="text/css" media="all" />
@endsection

@section('jsExtention')
<script type="text/javascript" src="{{ asset('js/chosen/chosen.jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/amcharts_weezam/amcharts.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/amcharts_weezam/serial.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/amcharts_weezam/export.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/amcharts_weezam/light.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/amcharts_weezam/amcharts_sample.js') }}"></script>

<script type="text/javascript">
	jQuery(document).ready(function () {
	var select = '#category';	
	$(select).trigger('chosen:updated');
                    $(select).trigger('change');
	});

</script>

@endsection

@section('content')		

<div class="one_half">
                	<div class="title"><h2 class="chart"><span>Filter Options</span></h2></div>
					
				
                   <form id="form2" class="stdform stdform2" method="post" action="">
                    	
                        
                        <p>
                        	<label> Category</label>	
							<span class="field">
									<select name="selection" id="category" data-placeholder="-">
									@foreach($category AS $r)
									<option value="{{ $r->category }}">{{ $r->category }}</option>
									@endforeach
                            </select>
							</span>	
                          </p>  
							<p>
                        	<label> Year & Period</label>	
							<span class="field">
									<select name="selection" id="year">
									<option value="">Choose One</option>
									<option value="1">Selection One</option>
									<option value="2">Selection Two</option>
									<option value="3">Selection Threeddddd</option>
									<option value="4">Selection Four</option>
                            </select>
							<select name="selection" id="period">
									<option value="">Choose One</option>
									<option value="1">Selection One</option>
									<option value="2">Selection Two</option>
									<option value="3">Selection Threeddddd</option>
									<option value="4">Selection Four</option>
                            </select>
							
							</span>	
                          </p> 
						  <p>
                        	<label> Report Type</label>	
							<span class="field">
									<select name="selection" id="reporttype">
									<option value="">Choose One</option>
									<option value="1">Selection One</option>
									<option value="2">Selection Two</option>
									<option value="3">Selection Three</option>
									<option value="4">Selection Four</option>
                            </select>
							</span>	
                          </p>  
                        	<p class="stdformbutton">
                        	<button class="submit radius2">Submit Button</button>
                            <input type="reset" class="reset radius2" value="Reset Button">
                        </p>
						</form>
					
			
                </div>
	<br clear="all">
	<br>
				<div>
                    	<div class="widgetbox uncollapsible ">
                            <div class="title "><h2 class="general"><span>[$Average Graph], {$YEAR | $PERIOD | $MONTH_2}</span></h2></div>
                            <div class="widgetcontent">
                                <div id="chartdiv3d"></div>	
                            </div><!--widgetcontent-->
                        </div><!--widgetbox-->
                    </div>
						
					 
                
		

<br clear="all">	
@endsection

@section('right')

@endsection
