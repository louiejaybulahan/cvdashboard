@extends('layouts.main',[
    'header' => [
        ['url' => '#','title' => 'List of Turnout','selected' => 'current'],
        ['url' => route('listeducation.index'),'title' => 'List of Non Compliant of Education','selected' => ''],
        ['url' => route('listhealth.index'),'title'  => 'List of Non Compliant of Health','selected' => ''],
        ['url' => route('listfds.index'),'title'  => 'List of Non Compliant of FDS','selected' => '']
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
    jQuery('#region').change(function(){
        var tmp = new Array();
        var select = '#province';
        var dataString = {'_token':'{{ csrf_token() }}', 'id':jQuery(this).val()};        
            jQuery.ajax({
                type: "POST", url:'{{ route('listeducation.getprovince') }}', data: dataString, dataType: 'json', cache: false,
                error: function (request, status, error) { jsMessage('Error Request'); },
                success: function (data) {
                    tmp = $(select).val();
                    $(select + ' option').remove();
                    if (data.list != '' && jQuery.isEmptyObject(data.list) == false) {
                        jQuery.each(data.list, function(key, val){ $(select).append('<option value="' + val.PROVINCE_ID +'">' + val.PROVINCE_NAME + '</option>'); });
                        if (tmp != null){ $(select).val(tmp); }
                    }
                    $(select).trigger('chosen:updated');
                    $(select).trigger('change');
                }
            });                
    });
    jQuery('#province').change(function(){
        var tmp = new Array();
        var select = '#municipality';        
        var dataString = {'_token':'{{ csrf_token() }}', 'id':jQuery(this).val()};         
            jQuery.ajax({
                type: "POST", url:'{{ route('listeducation.getmunicipality') }}', data: dataString, dataType: 'json', cache: false,
                error: function (request, status, error) { jsMessage('Error Request'); },
                success: function (data) {
                    tmp = $(select).val();
                    $(select + ' option').remove();
                    if (data.list != '' && jQuery.isEmptyObject(data.list) == false) {
                        jQuery.each(data.list, function(key, val){ $(select).append('<option value="' + val.CITY_ID +'">' + val.CITY_NAME + '</option>'); });
                        if (tmp != null){ $(select).val(tmp); }
                    }                
                    $(select).trigger('chosen:updated');
                    $(select).trigger('change');
                }
            });
    });    
    jQuery('#municipality').change(function(){
        var tmp = new Array();
        var select = '#brgy';
        var dataString = {'_token':'{{ csrf_token() }}', 'id':jQuery(this).val()};
            jQuery.ajax({
                type: "POST", url:'{{ route('listeducation.getbrgy') }}', data: dataString, dataType: 'json', cache: false,
                error: function (request, status, error) { jsMessage('Error Request'); },
                success: function (data) {
                    tmp = $(select).val();
                    $(select + ' option').remove();
                    if (data.list != '' && jQuery.isEmptyObject(data.list) == false) {
                        jQuery.each(data.list, function(key, val){ $(select).append('<option value="' + val.BRGY_ID +'">' + val.BRGY_NAME + '</option>'); });
                        if (tmp != null){ $(select).val(tmp); }
                    }
                    $(select).trigger('chosen:updated');
                }
            });
    });   
    jQuery('.chosen,#municipal').chosen({width: "95%"});
    var checkbox = function(cell, formatterParams){ return '<input type="checkbox" id="optHousehold" name="optHousehold" class="optHousehold" value="'+cell.getValue()+'" style="padding:0px; margin:0px;">'; }    
    jQuery("#resultTable").tabulator({
        height:"800px",
        columns:[            
            {title:'REGION', field:'region', frozen:true},
            {title:'PROVINCE', field:'province', frozen:true},
            {title:'CITY', field:'muni', frozen:true},
            {title:'BRGY', field:'brgy', frozen:true},
            // {title:'<center><input type="checkbox" id="checkAll" name="checkAll" style="padding:0px; margin:0px;"></center>', field:'householdid', align:"center", formatter: checkbox, headerSort:false, frozen:true},                        
            {title:'CATEGORY', field:'category', width: 150},
            {title:'SET', field:'set'},
            {title:'SET GROUP', field:'setgroup'},
            {title:'ELIGIBILITY', field:'eligibility'},
            {title:'NOT ATTENDED DOMINANT', field:'not_attend_dominant'},
            {title:'ATTEND DOMINANT', field:'attend_dominant'},
            {title:'ATTEND DELETED DOMINANT', field:'attend_del_dominant'},
            {title:'OUTSIDE', field:'outside'},
            {title:'MONITORED DOMINANT', field:'monitored_dominant'},
            {title:'ENCODED APPROVED', field:'encoded_approved'},
            {title:'SUBMITTED DEWORMING', field:'submitted_deworming'},
            {title:'NOT ENCODED APPROVED', field:'not_encoded_approved'},
            {title:'ENCODED UNDER FORCE MAJIURE', field:'encoded_under_forcem'},
            {title:'NON COMPLIANT', field:'non_compliant'},
            {title:'COMPLIANT', field:'compliant'},
            {title:'REMARKS 1', field:'remarks_1'},
            {title:'REMARKS 2', field:'remarks_2'},
            {title:'REMARKS 3', field:'remarks_3'},
            {title:'REMARKS 4', field:'remarks_4'},
            {title:'CLIENT STATUS', field:'client_status'},
            {title:'HHD', field:'sex'},
            {title:'GRADE GROUP', field:'grade_group'},
            {title:'IP', field:'ip'},
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
        'region' : jQuery('#region').val(),
        'province' : jQuery('#province').val(),
        'city' : jQuery('#municipality').val(),    
        'brgy' : jQuery('#brgy').val(),    
        'year' : jQuery('#year').val(),
        'period' : jQuery('#period').val(),  
        'month' : jQuery('#month').val(),        
        'category' : jQuery('#category').val(),
        'set' : jQuery('#set').val(),
        'setgroup' : jQuery('#setgroup').val(),
        'eligibility' : jQuery('#eligibility').val(),
        'not_attend_dominant' : jQuery('#not_attend_dominant').val(),                                                               
        'attend_dominant' : jQuery('#attend_dominant').val(),             
        'attend_del_dominant' : jQuery('#attend_del_dominant').val(),
        'outside' : jQuery('#outside').val(),
        'monitored_dominant' : jQuery('#monitored_dominant').val(),            
        'encoded_approved' : jQuery('#encoded_approved').val(),
        'submitted_deworming' : jQuery('#submitted_deworming').val(),
        'not_encoded_approved' : jQuery('#not_encoded_approved').val(),
        'encoded_under_forcem' : jQuery('#encoded_under_forcem').val(),
        'non_compliant' : jQuery('#non_compliant').val(),                                                               
        'compliant' : jQuery('#compliant').val(),             
        'remarks_1' : jQuery('#remarks_1').val(),        
        'remarks_2' : jQuery('#remarks_2').val(),
        'remarks_3' : jQuery('#remarks_3').val(),
        'remarks_4' : jQuery('#remarks_4').val(),
        'month' : jQuery('#month').val(),
        'client_status' : jQuery('#client_status').val(),
        'sex' : jQuery('#sex').val(),
        'grade_group' : jQuery('#grade_group').val(),
        'ip' : jQuery('#ip').val(),                                 
    };
}
function jsSearchData(page){
    var htmPage = '';
    var startPage = endPage = 0;
    var dataString = jsFilters(page);          
    jQuery('#loading').show();
    jQuery.ajax({
        type: "POST", url:'{{ route('listturnout.search') }}', data: dataString, dataType: 'json', cache: false,        
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
                <small class="desc" style="margin:0px;">Select as many </small>
            </p>   
            <p>
                <strong>Period:</strong><br>
                <select id="period" name="period" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_period AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many</small>
            </p>  
            <p>
                <strong>Month:</strong><br>
                <select id="month" name="month" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_month AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many ip</small>
            </p>
             <p>
                <strong>Region:</strong><br>
                <select id="region" name="region" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_region AS $r)
                        <option value="{{ $r->REGION_ID }}">{{ $r->REGION_NAME }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">select as many</small>
            </p>
            <p>
                <strong>Province:</strong><br>
                <select id="province" name="province" class="chosen" multiple="multiple" data-placeholder="-">                    
                    <option value=""></option>
                </select>
                <small class="desc" style="margin:0px;">Select as many</small>
            </p>
            <p>
                <strong>Municipality:</strong><br>
                <select id="municipality" name="municipality" class="chosen" multiple="multiple" data-placeholder="-">                    
                    <option value=""></option>
                </select>
                <small class="desc" style="margin:0px;">Select as many</small>
            </p>
            <p>
                <strong>Barangay:</strong><br>
                <select id="brgy" name="brgy" class="chosen" multiple="multiple" data-placeholder="-">                    
                    <option value=""></option>
                </select>
                <small class="desc" style="margin:0px;">Select as many</small>
            </p>   
            <p>
                <strong>IP:</strong><br>
                <select id="ip" name="ip" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_ip AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many ip</small>
            </p>                     
        </div>            
        <div class="one_fourth">     
            <p>
                <strong>Category:</strong><br>
                <select id="category" name="category" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_category AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many</small>
            </p>
            <p>
                <strong>Set:</strong><br>
                <select id="set" name="set" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_set AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many</small>
            </p>
            <p>
                <strong>Set Group:</strong><br>
                <select id="setgroup" name="setgroup" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_setgroup AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many</small>
            </p> 
            <p>
                <strong>Eligibility:</strong><br>
                <select id="eligibility" name="eligibility" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_eligibility AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many</small>
            </p>    
            <p>
                <strong>Not Attending Dominant:</strong><br>
                <select id="not_attend_dominant" name="not_attend_dominant" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_not_attend_dominant AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many</small>
            </p>  
            <p>
                <strong>Attend Deleted Dominant:</strong><br>
                <select id="attend_del_dominant" name="attend_del_dominant" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_attend_del_dominant AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many</small>
            </p> 
            <p>
                <strong>Outside:</strong><br>
                <select id="outside" name="outside" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_outside AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many</small>
            </p>       
        </div>               
        <div class="one_fourth">           
            <p>
                <strong>Monitored Dominant:</strong><br>
                <select id="monitored_dominant" name="monitored_dominant" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_outside AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many</small>
            </p>
            <p>
                <strong>Encoded Approved:</strong><br>
                <select id="encoded_approved" name="encoded_approved" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_encoded_approved AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many</small>
            </p>
            <p>
                <strong>Submitted Deworming:</strong><br>
                <select id="submitted_deworming" name="submitted_deworming" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_submitted_deworming AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many</small>
            </p>
            <p>
                <strong>Not Encoded Approved:</strong><br>
                <select id="not_encoded_approved" name="not_encoded_approved" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_not_encoded_approved AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many ip</small>
            </p>
            <p>
                <strong>Encoded Under Force Majuore:</strong><br>
                <select id="encoded_under_forcem" name="encoded_under_forcem" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_ip AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many ip</small>
            </p>
            <p>
                <strong>Non Compliant:</strong><br>
                <select id="non_compliant" name="non_compliant" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_non_compliant AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many ip</small>
            </p>   
            <p>
                <strong>Compliant:</strong><br>
                <select id="compliant" name="compliant" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_compliant AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many ip</small>
            </p>         
        </div>   
        <div class="one_fourth last">                            
            <p>
                <strong>Remarks 1:</strong><br>
                <select id="remarks_1" name="remarks_1" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_remarks_1 AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many ip</small>
            </p>
            <p>
                <strong>Remarks 2:</strong><br>
                <select id="remarks_2" name="remarks_2" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_remarks_2 AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many ip</small>
            </p>
            <p>
                <strong>Remarks 3:</strong><br>
                <select id="remarks_3" name="remarks_3" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_remarks_3 AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many ip</small>
            </p>
            <p>
                <strong>Remarks 4:</strong><br>
                <select id="remarks_4" name="remarks_4" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_remarks_4 AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many ip</small>
            </p>            
            <p>
                <strong>Client Status:</strong><br>
                <select id="client_status" name="client_status" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_client_status AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many ip</small>
            </p>
            <p>
                <strong>Sex:</strong><br>
                <select id="sex" name="sex" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_sex AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many ip</small>
            </p>
            <p>
                <strong>Grade Group:</strong><br>
                <select id="grade_group" name="grade_group" class="chosen" multiple="multiple" data-placeholder="-">                    
                    @foreach($_grade_group AS $r)
                    <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;">Select as many ip</small>
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