@extends('layouts.main')

@section('title', 'Backend Processing')
@section('optLayout','noright')

@section('cssExtention')
<link rel="stylesheet" href="js/modal/jquery.modal.min.css" type="text/css" media="screen" />
@endsection

@section('jsExtention')
<script type="text/javascript" src="{{ asset('js/plugins/jquery.jgrowl.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/modal/jquery.modal.min.js') }}"></script>


<script type="text/javascript">
var jsLastRender = 0;
var jsCheckScheduled = null;

jQuery(window).ready(function () {  
    // startChecking(1);
    jsGetScheduled();
    jQuery('.btnBack').click(function () {
        jQuery('#divNew').hide();        
        jQuery('#divList').fadeIn();
    });   
    jQuery('#btnNew').click(function(){        
        $('#divList').hide();
        $('#divNew').fadeIn();
    });    
    jQuery('#btnSave').click(function(){
        jQuery(this).attr('class','btn btn-default');        
        jQuery(this).attr('class','stdbtn btn_blue').prop('disabled',true);
        jQuery('#frmSubmitForm [name="'+tokenName +'"]').val(jsToken(''));
        var dataString = $('#frmSubmitForm').serialize();
        $('#divListError').fadeOut();
        jQuery.ajax({
            type: "POST", url:  jQuery('#frmSubmitForm').attr('action'), data: dataString, dataType: 'json', cache: false,
            error: function (request, status, error) { jsMessage('Error Request'); },
            success: function (data) {
                jQuery.jGrowl(data.msg);               
                if (data.flag == 0){                                           
                    if(data.errorlist!='' && jQuery.isEmptyObject(data.errorlist)==false){ 
                        var strError = '';
                        jQuery.each(data.errorlist,function(key,val){
                           strError += '* '+val+'<br>';
                        });
                        $('#divListError').html(strError).fadeIn();
                    }                    
                }else{ $('#frmSubmitForm').trigger('reset'); location.reload(); }  
                jsToken(data.token);               
            }
        }).done(function(){
            jQuery('#btnSave').attr('class','stdbtn btn_black').prop('disabled',false);            
        });
    });        
});  
function jsMessage(message) { jQuery.jGrowl(message); return false; }
function jsDelete(id){
    var dataString = {'_token':'{{ csrf_token() }}', 'id':id};
    jQuery.ajax({
    type: "POST", url: "{{ route('backgroundprocess.remove') }}", data: dataString, dataType: 'json', cache: false,
            error: function (request, status, error) { jsMessage('Error Request'); },
            success: function (data) {
                jsMessage(data.msg);
                if (data.flag == 1){ jQuery('#row_' + data.id).remove(); } 
                jsToken(data.token);                 
            }
    });
    return false;
}

function enabledUploadFile(){
    jQuery('#btnUpload').attr('class','stdbtn btn_black').removeAttr('disabled');
    jQuery('#loading').fadeOut();
    return false;
}
function jsRender(){
    jQuery('#displayOutput').attr('src',"{{ route('uploadfile.renderfile') }}");
    return false;
}
// function startChecking() {
//     jsCheckScheduled = setInterval(function () {        
//         jsCheckSchduled();
//     }, 3000);
// }
// function stopChecking(){
//     clearInterval(jsCheckScheduled);
//     return false;
// }
function jsGetScheduled(){
    var index = eval(jQuery('#rowIndex').val());
    var url = "{{ route('backgroundprocess.checkscript',['row'=> '']) }}";
    $.getJSON(url+index, function( data ) {             
        var htmHistory = '';     
        if(data.history!='' && jQuery.isEmptyObject(data.history)==false){             
            jQuery.each(data.history,function(key,val){
                htmHistory += '* '+key + ' : ' + val+'<br>';
            });            
        }      
        jsLoadScriptFound(data.url);
        jQuery('#htmScriptname').html(data.scriptname);
        jQuery('#htmUrl').html(data.url);
        jQuery('#htmParameters').html(data.parameters);        
        jQuery('#htmRunin').html(data.run_in);        
        jQuery('#htmHistory').html(htmHistory);
        jQuery('#rowIndex').val(data.rowIndex);   

    });
    return false;
}
function jsQueryProcessDone(){    
    jsGetScheduled();
    return false;
}
function jsLoadScriptFound(jsUrl){
    jQuery('#displayOutput').attr('src',jsUrl);    
    /*
    jQuery('#displayOutput').html(' Loading ... ');        
    var ifr=$('<iframe/>', {
        id:'MainPopupIframe',
        src: jsUrl,
        style:'min-height: 268px;border: 1px #000 dashed; overflow-y: scroll; width: 100%;',
        load:function(){                
            jsMessage('Script is Done');
        }
    });
    $('#displayOutput').html(ifr);                
    */
    return false;
}
</script>
@endsection

@section('content')  


<div class="">	
    <div id="divList">  
        <div class="contenttitle">
            <h2 class="form"><span>Script List List</span></h2>
        </div>	   
        <div class="tableoptions">
            <button class="deletebutton radius3" title="table1" id="btnNew">Add Script</button> &nbsp;
        </div>
        <input type="hidden" id="rowIndex" name="rowIndex" value="0">
        <table cellpadding="0" cellspacing="0" border="0" class="stdtable stdtablecb">
            <colgroup>
                <col class="con0" width="300">
                <col class="con1" >
                <col class="con0" width="300">
                <col class="con1" width="150">
                <col class="con0" width="150">
                <col class="con1" width="100">
            </colgroup>
            <thead>
                <tr>
                    <th class="head0">Scriptname</th>
                    <th class="head1">Source</th>
                    <th class="head0">Parameter</th>
                    <th class="head1">Run In</th>
                    <th class="head0">Time</th>
                    <th class="head1">Options</th>
                </tr>
            </thead>
            <tfoot>
                <tr>                    
                    <th class="head0">Scriptname</th>
                    <th class="head1">Source</th>
                    <th class="head0">Parameter</th>
                    <th class="head1">Run In</th>
                    <th class="head0">Time</th>
                    <th class="head1">Options</th>
                </tr>
            </tfoot>
            <tbody>
                @foreach($processlist AS $r)
                    <tr id="row_{{ $r->id }}">
                        <td>
                            <input type="hidden" id="url_{{ $r->id }}" name="url_{{ $r->id }}" value="{{ $r->url }}"> 
                            {{ $r->scriptname }}
                        </td>
                        <td>{{ $r->url }} </td>
                        <td>{{ $r->parameters }} </td>
                        <td>{{ $r->run_in }} </td>
                        <td>{{ $r->time }} </td>                        
                        <td><center><a href="#Delete-{{ $r->id }}" class="stdbtn"  style="opacity: 1;" onclick="jsDelete('{{ $r->id }}');">Delete</a></center></td>
                    </tr>
                @endforeach
            </tbody>
        </table> 
    </div> 
    <div id="divNew" style="display:none;">        
        <div class="contenttitle">
            <h2 class="form"><span>Add New Script</span></h2>
        </div>	   
        {{ Form::open(['route' => 'backgroundprocess.addscript','class' => 'stdform stdform2','id' => 'frmSubmitForm','novalidate' => 'novalidate','onsubmit' => 'return false;']) }}        
            <p>
                <label>Script Name</label>
                <span class="field"><input type="text" name="scriptname" id="scriptname" class="longinput"></span>
            </p>            
            <p>
                <label>Url Source</label>
                <span class="field"><input type="text" name="url" id="url" class="longinput"></span>
            </p>
            <p>
                <label>Parameters</label>
                <span class="field"><input type="text" name="parameters" id="parameters" class="longinput"></span>
            </p>
            <p>
                <label>Run in</label>
                <span class="field">
                    <select id="run_in" name="run_in">
                        <option value="Year">Every Year</option>
                        <option value="Month">Every Month</option>                        
                        <option value="Days">Every Day</option>
                        <!-- option value="Hour">Every Hour</option -->
                        <!-- option value="Once">Once</option -->
                        <!-- option value="6">Every Weeks</option -->
                    </select>
                </span>                
            </p>            
            <p>
                <label>Time to run</label>
                <span class="field">
                    <select id="time" name="time">
                        <option>1</option>
                        <option>2</option>
                        <option>3</option>
                        <option>4</option>
                        <option>5</option>
                        <option>6</option>
                        <option>7</option>
                        <option>8</option>
                        <option>9</option>
                        <option>10</option>
                        <option>11</option>
                        <option>12</option>
                        <option>13</option>
                        <option>14</option>
                        <option>15</option>
                        <option>16</option>
                        <option>17</option>
                        <option>18</option>
                        <option>19</option>
                        <option>20</option>
                        <option>21</option>
                        <option>22</option>
                        <option>23</option>
                        <option>24</option>
                    </select>
                    <small class="desc" style="margin:0px;">Note : 24hrs format</small>
                </span>                
            </p>                                                                                                            
            <p class="stdformbutton">
                <span id="divListError" style="color:red;display:none;"></span><br>
                <button class="submit radius2 btnBack">Back</button>
                <button class="submit radius2" id="btnSave">Save Script</button>                
            </p>
        {{ Form::close() }} 
    </div>     

        <br clear="all">
        
        <div class="one_half">
            <div class="widgetbox" >
                <div class="title"><h2 class="tabbed"><span>Todays Schedule</span></h2></div>
                <div class="widgetcontent">                
                        <p>
                            <h2>Files Read :
                                <u id="htmScriptname"></u>
                            </h2>
                        </p>
                        <br clear="all">
                        <p>
                            <h2>Source url :
                                <u id="htmUrl"></u>
                            </h2>
                        </p>
                        <br clear="all">
                        <p>
                            <h2>Parameters :
                                <u id="htmParameters"></u>
                            </h2>
                        </p>
                        <br clear="all">
                        <p>
                            <h2>Run this Application : Every
                                <u id="htmRunin"></u>
                            </h2>
                        </p> 
                        <br clear="all">
                        <p>
                            <h2>Last Script Run</h2>
                            <span id="htmHistory"></span>
                        </p>
                </div><!--widgetcontent-->         
            </div>
        </div>   
        <div class="one_half last">
            <div class="widgetbox" >
                <div class="title"><h2 class="tabbed"><span>Display Script</span></h2></div>                
                <div class="widgetcontent padding0" id="displayOutputs">                
                    <iframe name="displayOutput" id="displayOutput" style="min-height: 268px;border: 1px #000 dashed; overflow-y: scroll; width: 100%;" ></iframe>
                </div><!--widgetcontent-->         
            </div>
        </div> 

</div>  
<br clear="all">

@endsection