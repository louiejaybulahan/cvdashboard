@extends('layouts.main')

@section('title', 'Upload Turn Out Data')
@section('optLayout','noright')

@section('cssExtention')
<link rel="stylesheet" href="js/modal/jquery.modal.min.css" type="text/css" media="screen" />
@endsection

@section('jsExtention')
<script type="text/javascript" src="{{ asset('js/plugins/jquery.jgrowl.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/modal/jquery.modal.min.js') }}"></script>


<script type="text/javascript">
var jsLastRender = 0;
jQuery(window).ready(function () {    
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
</script>
@endsection

@section('content')  


<div class="one_half">	
    <div id="divList">  
        <div class="contenttitle">
            <h2 class="form"><span>Process List</span></h2>
        </div>	   
        <div class="tableoptions">
            <button class="deletebutton radius3" title="table1" id="btnNew">Add Script</button> &nbsp;
        </div>
        <table cellpadding="0" cellspacing="0" border="0" class="stdtable stdtablecb">
            <colgroup>
                <col class="con0" width="200">
                <col class="con1" >
                <col class="con0" width="100">
                <col class="con1" width="100">
            </colgroup>
            <thead>
                <tr>
                    <th class="head0">Scriptname</th>
                    <th class="head1">Source</th>
                    <th class="head0">Status</th>
                    <th class="head1">Options</th>
                </tr>
            </thead>
            <tfoot>
                <tr>                    
                <th class="head0">Scriptname</th>
                    <th class="head1">Source</th>
                    <th class="head0">Status</th>
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
                        <td>{{ $r->url }}</td>
                        <td id="tdStatus_{{ $r->id }}">
                            {{ ($r->status)?'process':'' }}                            
                        </td>
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
            <p class="stdformbutton">
                <span id="divListError" style="color:red;display:none;"></span><br>
                <button class="submit radius2 btnBack">Back</button>
                <button class="submit radius2" id="btnSave">Save Script</button>                
            </p>
        {{ Form::close() }}  <br>
        <p>
            
        </p>
    </div>
</div>

<div class="one_half last">
    <div class="widgetbox" >
        <div class="title"><h2 class="tabbed"><span>Results</span></h2></div>
        <div class="widgetcontent padding0">                
             <iframe name="displayOutput" id="displayOutput" style="min-height: 485px;border: 1px #000 dashed; overflow-y: scroll; width: 100%;" ></iframe>
        </div><!--widgetcontent-->         
    </div>
</div>    

<br clear="all">
@endsection