@extends('layouts.main')

@section('title', 'Settings')
@section('optLayout','noright')

@section('cssExtention')
<link rel="stylesheet" href="js/modal/jquery.modal.min.css" type="text/css" media="screen" />
<link rel="stylesheet" href="js/chosen/chosen.css" type="text/css" media="screen" />
<style>
.dataTables_wrapper input { border: 1px solid #ccc; padding: 6px 5px 7px 5px; width: auto; }
</style>
@endsection

@section('jsExtention')
<script type="text/javascript" src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.jgrowl.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/modal/jquery.modal.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/chosen/chosen.jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/chosen/init.js') }}"></script>


<script type="text/javascript">
jQuery(document).ready(function (){	
	jQuery('.btnSaveConfig').click(function(){
		var form = '#formSettings';
		jQuery.ajax({
            type: "POST", url: jQuery(form).attr('action'), data: jQuery(form).serialize() , dataType: 'json', cache: false,
            error: function (request, status, error) { jQuery.jGrowl('Error Request'); },
            success: function (data) { jQuery.jGrowl(data.msg);  }
        });
		return false;
	});
	jQuery('.rbtnRebuild').click(function(){	
		var yr = jQuery(this).attr('href');
		var dataString = {'_token':'{{ csrf_token() }}', 'year': yr.replace('#','') };	
		jQuery.ajax({
            type: "POST", url: '{{ route('settings.rebuildfilter') }}', data: dataString, dataType: 'json', cache: false,
            error: function (request, status, error) { jQuery.jGrowl('Error Request'); },
            success: function (data) { jQuery.jGrowl(data.msg);  }
        });
		return false;
	});
	jQuery('#btnNewStorage').click(function(){		
		var dataString = {'_token':'{{ csrf_token() }}'};	
		jQuery.getJSON('{{ route('settings.newstorage') }}',function(data){
            if(data.flag){ location.reload(); }
            else{  jQuery.jGrowl(data.msg);  }
        });
		return false;
	});
});
   
</script>
@endsection

@section('content')
{{ Form::open(['route' => 'settings.save','class' => 'stdform stdform2','id' => 'formSettings','novalidate' => 'novalidate','onsubmit' => 'return false;' ]) }}        
<div class="one_half">	
	<div class="contenttitle">
		<h2 class="form"><span>GLOBAL CONFIGURATION</span></h2>
	</div>	
	<p>		
    	<label>Enable Audit Trail</label>
        <span class="field"><select name="configAuditTrail" id="configAuditTrail" class="longinput">
        	<option value="1" {{ $config['AUDIT_TRAIL']==1?'selected="selected"':'' }}	>YES</option>
            <option value="0" {{ $config['AUDIT_TRAIL']!=1?'selected="selected"':'' }}	>NO</option>            
        </select><small class="desc" style="margin:0px;">Audit Trail description.</small></span>

    </p>
	<p class="stdformbutton">
    	<button type="button" class="submit radius2 btnSaveConfig">Save Configuration</button>        
    </p>
	<div class="contenttitle">
		<h2 class="form"><span>SYSTEM CONFIGURATION</span></h2>
	</div>
	<p>
    	<label>Begin Period</label>
        <span class="field">
        	<input type="text" name="configPeriodStart" id="configPeriodStart" class="longinput" value="{{ $config['PERIOD_START']}}">
        	<small class="desc" style="margin:0px;">Period Begin.</small>
        </span>
    </p>
    <p>
    	<label>Current Period</label>
        <span class="field">
        	<input type="text" name="configPeriodCurrent" id="configPeriodCurrent" class="longinput" value="{{ $config['PERIOD_CURRENT']}}">
        	<small class="desc" style="margin:0px;">Current Period descirption.</small>
        </span>
    </p>
    <p class="stdformbutton">
    	<button type="button" class="submit radius2 btnSaveConfig">Save Configuration</button>        
    </p>			
</div>

<div class="one_half last">
    <div class="widgetbox" >
        <div class="title"><h2 class="tabbed"><span>Data Storage</span></h2></div>
        <div class="widgetcontent padding0">
            <ul class="activitylist">
            	@foreach($filters as $r)
            	<li><a href="#{{ $r['year'] }}" class="rbtnRebuild">Grants <strong>{{ $r['year'] }}</strong> <span><i>rebuild filters</i></span></a></li>
            	@endforeach
                <!-- li><a href=""><strong>Paran Meller</strong> added <strong>23 users</strong> <span>Yesterday</span></a></li>
                <li><a href=""><strong>Owen Lee</strong> added <strong>2 users</strong> <span>2 days ago</span></a></li>
                <li><a href=""><strong>Jane Call</strong> sent a message <span>5 days ago</span></a></li-->
                <li style="padding:5px;">&nbsp;<button class="radius2" id="btnNewStorage">Create New Storage for this Year</button></li>
            </ul>
        </div><!--widgetcontent-->
    </div>
</div>    
{{ Form::close() }}
<br clear="all">
@endsection