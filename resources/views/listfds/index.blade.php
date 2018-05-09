@extends('layouts.main',[
    'header' => [
        ['url' => route('listeducation.index'),'title' => 'List of Non Compliant of Education','selected' => ''],
        ['url' => route('listhealth.index'),'title'  => 'List of Non Compliant of Health','selected' => ''],
        ['url' => '#','title'  => 'List of Non Compliant of FDS','selected' => 'current']
    ]
])

@section('title', 'List of Beneficiary')
@section('optLayout','noright')

@section('cssExtention')
<link rel="stylesheet" href="js/modal/jquery.modal.css" type="text/css" media="screen" />
<link rel="stylesheet" href="js/chosen/chosen.css" type="text/css" media="screen" />
<link rel="stylesheet" href="{{ asset('js/jquery-confirm/css/jquery-confirm.css') }}" type="text/css" media="screen" />
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

<link href="{{ asset('js/tabulator/dist/css/tabulator.css') }}" rel="stylesheet">
<script type="text/javascript" src="{{ asset('js/tabulator/dist/js/tabulator.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-confirm/js/jquery-confirm.js') }}"></script>

<script type="text/javascript">
jQuery(document).ready(function () {
    jQuery('.chosen,#municipal').chosen({width: "95%"});
    var checkbox = function(cell, formatterParams){ return '<input type="checkbox" id="optHousehold" name="optHousehold" class="optHousehold" value="'+cell.getValue()+'" style="padding:0px; margin:0px;">'; }    
    jQuery("#resultTable").tabulator({
        height:"800px",
        columns:[            
            {title:'REGION', field:'region', frozen:true},
            {title:'PROVINCE', field:'province', frozen:true},
            {title:'CITY', field:'muni', frozen:true},
            {title:'BRGY', field:'brgy', frozen:true},
            {title:'<center><input type="checkbox" id="checkAll" name="checkAll" style="padding:0px; margin:0px;"></center>', field:'householdid', align:"center", formatter: checkbox, headerSort:false, frozen:true},            
            {title:'PSGC', field:'psgc'},                                                
            {title:'HH ID', field:'hh_id', width: 70},
            {title:'ENTRY ID', field:'entry_id', width: 70},
            {title:'HH STATUS', field:'hh_status', width: 70},
            {title:'LASTNAME', field:'lastname', width: 70},            
            {title:'FIRSTNAME', field:'firstname'},            
            {title:'MIDDLENAME', field:'middlename', width: 70},
            {title:'EXT', field:'ext', width: 40},
            {title:'SEX', field:'sex', width: 50},
            {title:'BIRTHDAY', field:'bday', width: 70},                        
            {title:'IP', field:'ip', width: 60},            
            {title:'MONTH', field:'month'},            
            {title:'YEAR', field:'year'},
            {title:'PERIOD', field:'period', width: 60},            
            {title:'#', field:'counter', sorter:'number'},                        
        ],      
    });   

    jQuery('#btnSearch').click(function(){ jsSearchData(1); });
    jQuery('#btnSearch').trigger('click');        
    jQuery('#checkAll').change(function(){
        if(jQuery(this).is(':checked')){ jQuery('.optHousehold').prop('checked',true); }
        else{ jQuery('.optHousehold').prop('checked',false); }
    });
    jQuery('#btnRebuild').click(function(){
        jQuery.getJSON('{{ route('listfds.rebuildfilter') }}',function(data){
            location.reload();
        });
    });
    jQuery('#btnActions').click(function(){
        var actions = jQuery('#actions').val();
        if(actions=='summary'){ jsShowSummary(); }        
    });
    jQuery('#btnPaidUnpaid').click(function(){ jsPaidUnpaid(); });
});
function jsMessage(message) { jQuery.jGrowl(message); return false; }
function jsFilters(page){
    return {
        '_token':'{{ csrf_token() }}',                
        'page' : page,
        'order' : jQuery('#order').val(),
        'sort' : jQuery('#sort').val(),        
        'limit': jQuery('#limit').val(),  
        'year' : jQuery('#year').val(),
        'region' : jQuery('#region').val(),
        'muni' : jQuery('#muni').val(),
        'city' : jQuery('#city').val(),       
        'brgy' : jQuery('#brgy').val(),            
        'psgc' : jQuery('#psgc').val(),                    
        'hh_id' : jQuery('#hh_id').val(),
        'entry_id' : jQuery('#entry_id').val(),
        'hh_status' : jQuery('#hh_status').val(),
        'lastname' : jQuery('#lastname').val(),
        'firstname' : jQuery('#firstname').val(),                                                               
        'middlename' : jQuery('#middlename').val(),             
        'sex' : jQuery('#sex').val(),
        'bday' : jQuery('#bday').val(),
        'ip' : jQuery('#ip').val(),
        'month' : jQuery('#month').val(),        
        'period' : jQuery('#period').val(),                   
    };
}
function jsSearchData(page){
    var htmPage = '';
    var startPage = endPage = 0;
    var dataString = jsFilters(page);          
    jQuery('#loading').show();
    jQuery.ajax({
        type: "POST", url:'{{ route('listfds.search') }}', data: dataString, dataType: 'json', cache: false,        
        error: function (request, status, error) { jsMessage('Error Request'); },
        success: function (data) {              
            startPage = 1;
            endPage = data.pages;
            if(eval(data.pages)>=5){                    
                startPage = data.pageActive - 10;
                endPage = data.pageActive + 10;
                if(startPage<1) startPage = 1;
                if(endPage>data.pages) endPage = data.pages;   
                console.log(endPage);                 
            }                          
            for(var i = startPage; i <= endPage; i++ ){                    
                htmPage += '<span class="'+((data.pageActive==i)?'paginate_active':'paginate_button')+'" id="page_'+i+'" onclick="'+ ((data.pageActive!=i)?'jsSearchData(\''+i+'\',false);':'') +'">'+i+'</span>';
            }
            $('#dyntable_paginate').html('<span class="first paginate_button paginate_button_disabled" id="dyntable_first" onclick="'+ 'jsSearchData(1);' +'">First</span><span>'+htmPage+'</span><span class="last paginate_button" id="dyntable_last" onclick="'+ 'jsSearchData(\''+data.pages+'\');' +'">Last</span>');                
            $('#dyntable_info').html('Total Records Found: <strong>'+data.rows+'</strong>');            
            $('#resultTable').tabulator("setData",data.tableData);
        }
    }).done(function(){ jQuery('#checkAll').prop('checked',false); jQuery('#loading').fadeOut(); });
    
    /*
    var ajaxConfig = {
        type:'POST', //set request type to Position     
        //contentType: 'application/json; charset=utf-8', //set specific content type       
        ajaxResponse:function(url, params, response){ console.log('hello world');  },
        //success: function(data){ console.log(data); }
    };
    $('#resultTable').tabulator("setData",'{{ route('listfds.search') }}', dataString, ajaxConfig);
    */
    return false;
}
function jsShowSummary(){
    event.preventDefault();
    jQuery.get('{{ route('listfds.summary') }}', function(html) { jQuery('#divSummary').html(html).modal({width:'1000px'}); });
    return false;
}
</script>
@endsection

@section('content')

<div id="divSummary" width="1000px;"></div>
{{ Form::open(['route' => 'listfds.search','class' => 'stdform','id' => 'formUser','novalidate' => 'novalidate' ]) }}        
<div class="widgetbox">
    <div class="title widgettoggle"><h2 class="general"><span>Filters</span></h2></div>        
    <div class="widgetcontent" style="display:block;">
        <div class="one_fourth">    
             <p>
                <strong>Region:</strong><br>
                <select id="region" name="region" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_region AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">select as many region</small>
            </p>
            <p>
                <strong>Province:</strong><br>
                <select id="province" name="province" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_province AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many province</small>
            </p>
            <p>
                <strong>Municipality:</strong><br>
                <select id="municipality" name="municipality" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_municipality AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many municipality</small>
            </p>
            <p>
                <strong>Barangay:</strong><br>
                <select id="brgy" name="brgy" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_brgy AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many barangay</small>
            </p>            
        </div>            
        <div class="one_fourth">             
            <p>
                <strong>Year:</strong><br>
                <select id="year" name="year" class="chosen" multiple="multiple" data-placeholder="-">                            
                    @php
                        $detect = false;
                        foreach($_year AS $r):
                            $selected = ($detect==false)?'selected="selected"':'';
                            echo '<option value="'.$r.'" '.$selected.'>'.$r.'</option>';
                            $detect = true;
                        endforeach;
                    @endphp
                </select>
                <small class="desc" style="margin:0px;">Select as many year </small>
            </p>              
            <!-- p>
                <strong>PSGC:</strong><br>
                <select id="psgc" name="psgc" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @ foreach($_psgc AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @ endforeach
                </select>
                <small class="desc" style="margin:0px;">select as many psgc</small>
            </p -->   
            <p>
                <strong>Household Status:</strong><br>
                <select id="hh_status" name="hh_status" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_hh_status AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many household status</small>
            </p>  
            <p>
                <strong>Month:</strong><br>
                <select id="month" name="month" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_month AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many month</small>
            </p>            
        </div>               
        <div class="one_fourth">
            <p>
                <strong>IP:</strong><br>
                <select id="ip" name="ip" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_ip AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many ip</small>
            </p>           
            <p>
                <strong>Entry ID:</strong><br>
                <input style="padding:5px 5px;" type="text" name="entry_id" id="entry_id" class="longinput">
                <small class="desc" style="margin:0px;">Entry ID of the beneficiary. search type : ---%</small>
            </p> 
            <p>
                <strong>Household ID:</strong><br>
                <input style="padding:5px 5px;" type="text" name="hh_id" id="hh_id" class="longinput">
                <small class="desc" style="margin:0px;">Household ID of the beneficiary. search type : ---%</small>
            </p> 
            <p>
                <strong>Period:</strong><br>
                <select id="period" name="period" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_period AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many period</small>
            </p>              
        </div>   
        <div class="one_fourth last">            

            <p>
                <strong>Firstname:</strong><br>
                <input style="padding:5px 5px;" type="text" name="firsntmae" id="firstname" class="longinput">
                <small class="desc" style="margin:0px;">Firstname of the beneficiary. search type : ---%</small>
            </p> 
            <p>
                <strong>Lastname:</strong><br>
                <input style="padding:5px 5px;" type="text" name="lastname" id="lastname" class="longinput">
                <small class="desc" style="margin:0px;">Lastname of the beneficiary. search type : ---%</small>
            </p> 
            <p>
                <strong>Middlename:</strong><br>
                <input style="padding:5px 5px;" type="text" name="middlename" id="middlename" class="longinput">
                <small class="desc" style="margin:0px;">Middlename of the beneficiary. search type : ---%</small>
            </p>
             <p>
                <strong>Birthday:</strong><br>
                <input style="padding:5px 5px;" type="text" name="bday" id="bday" class="longinput">
                <small class="desc" style="margin:0px;">Birthday. search type : xxxx-xx-xx% (year-month-day)</small>
            </p>
        </div>           
        <br clear="all">
    </div>
</div>

{{ Form::close() }}

<br clear="all">
<div class="contenttitle radiusbottom0"><h2 class="table"><span>Result</span></h2></div>
<div class="dataTables_wrapper" >
    <div id="dyntable_length" class="dataTables_length">   
        <label><strong>Showing </strong></label>
        <select size="1" name="dyntable_length" id="limit" name="limit">                
            <option value="50" selected="selected">50</option>
            <option value="100">100</option>
            <option value="150">150</option>
            <option value="300">300</option>
            <option value="400">400</option>
            <option value="500">500</option>
        </select>
        <strong>entries</strong>
        <span style="margin-right: 20px;">&nbsp;</span>        
        <strong>Sort:</strong>
        <select id="order" name="order"  data-placeholder="-">                                        
            <option value="-">-None-</option>
            <option value="region">Region</option>
            <option value="province">Provinc</option>
            <option value="muni">Municipality/City</option>
            <option value="brgy">Barangay</option>
            <option value="psgc">PSGC</option>            
            <option value="hh_id">Household ID</option>
            <option value="entry_id">Entry ID</option>
            <option value="hh_status">STATUS</option>
            <option value="lastname">Lastname</option>
            <option value="firstname">Firstname</option>
            <option value="middlename">Middlename</option>
            <option value="birthday">Birthday</option>            
            <option value="ip">IP</option>
            <option value="sex">Grade</option>
            <option value="month">Month</option>
            <option value="year">Year</option>
            <option value="period">Period</option>            
        </select>                
        <select id="sort" name="sort"  data-placeholder="">                                        
            <option value="-">-None-</option>
            <option value="ASC">ASC</option>
            <option value="DESC">DESC</option>
        </select>        
        <a href="#search" id="btnSearch" class="stdbtn btn_blue"><span>Search</span></a>   
        <!-- a href="#rebuild" id="btnRebuild" class="stdbtn" ><span>Rebuild Filter</span></a -->
        &nbsp;&nbsp;&nbsp;<img src="{{ asset('images/loading.gif') }}" id="loading"></img>
    </div>
    <div class="dataTables_filter" id="dyntable_filter">        
         <label><strong>Actions :</strong></label>
        <select id="actions" name="actions"> 
            <option value="summary">Summary Report</option>               
            <option value="xlsx">Export Excel</option>
            <option value="pdfConvert">Convert to PDF</option>
            <option value="print">Print Report</option>            
            <option value="pdfReport">PDF Report</option>            
        </select>
        <a href="#actions" id="btnActions" class="stdbtn btn_blue"><span>Go</span></a>   
    </div>
    <div id="resultTable"></div>
    <div class="dataTables_info" id="dyntable_info"></div>
    <div class="dataTables_paginate paging_full_numbers" id="dyntable_paginate"></div>
</div>    
<br clear="all">

@endsection