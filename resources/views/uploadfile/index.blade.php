@extends('layouts.main',[
    'header' => [
        ['url' => '#','title' => 'Import Turn Out Data','selected' => 'current'],
        ['url' => route('uploadfilenoncom.index'),'title'  => 'Import Non Compliant FDS, Health Center, Education','selected' => '']
    ]
])

@section('title', 'Upload Turn Out Data')
@section('optLayout','noright')

@section('cssExtention')
<link rel="stylesheet" href="{{ asset('js/modal/jquery.modal.min.css') }}" type="text/css" media="screen" />
@endsection

@section('jsExtention')
<script type="text/javascript" src="{{ asset('js/plugins/jquery.jgrowl.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/modal/jquery.modal.min.js') }}"></script>


<script type="text/javascript">
jQuery(window).ready(function () {    
    /* 
    // single file uploading   
    jQuery('#btnUpload').on('click', function(e){   
        var errorMessage = '';
        e.preventDefault();                               
        jQuery(this).attr('class','btn btn-default');
        jQuery(this).prop('disabled',true);                            
        jQuery('#frmUpload [name="'+tokenName +'"]').val(jsToken(''));
        jQuery(this).attr('class','stdbtn btn_blue').prop('disabled',true);
        jQuery('#loading').fadeIn();        
        var formData = new FormData(jQuery('#frmUpload')[0]);    
        jQuery.ajax({
            url: jQuery('#frmUpload').attr('action'),
            type: 'POST',
            data: formData,
            async: false,            
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function() {   },
            success: function (data) {                             
                jsToken(data.tokenValue);                    
                if(data.flag==1){ jQuery('#displayOutput').attr('src',"{{ route('uploadfile.renderfile',['filename' => '']) }}"+data.filename); }
                else{                    
                    $.each(data.error, function(key,val){ errorMessage += ' * '+ val +'<br>'; });                    
                    jQuery('#showError').html(errorMessage).fadeIn();
                    jsMessage('Error Request: Invalid File Upload','danger');
                }                  
                jQuery('#multipleCompressFileTurnout').val('');                    
            },
            error: function (request, status, error) { jsMessage('Error Request: Invalid File Upload','danger'); },
        });                                            		
        return false;
    });
    */
    jQuery('#btnUpload').on('click', function(e){   
        var errorMessage = '';
        e.preventDefault();                               
        jQuery(this).attr('class','btn btn-default');
        jQuery(this).prop('disabled',true);                            
        jQuery('#frmUpload [name="'+tokenName +'"]').val(jsToken(''));
        jQuery(this).attr('class','stdbtn btn_blue').prop('disabled',true);
        jQuery('#loading').fadeIn();        
        var formData = new FormData(jQuery('#frmUpload')[0]);    
        jQuery.ajax({
            url: jQuery('#frmUpload').attr('action'),
            type: 'POST',
            data: formData,
            async: false,            
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            beforeSend: function() {   },
            success: function (data) {                             
                jsToken(data.tokenValue);                    
                if(data.flag==1){ jsRender(); }
                else{                    
                    $.each(data.error, function(key,val){ errorMessage += ' * '+ val +'<br>'; });                    
                    jQuery('#showError').html(errorMessage).fadeIn();
                    jsMessage('Error Request: Invalid File Upload','danger');
                }                  
                jQuery('#multipleCompressFileTurnout').val('');                    
            },
            error: function (request, status, error) { jsMessage('Error Request: Invalid File Upload','danger'); },
        });                                                 
        return false;
    });
    function jsMessage(message) { jQuery.jGrowl(message); return false; }    
});  

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
    
{{ Form::open(['route' => 'uploadfile.loadfile','class' => 'stdform stdform2','id' => 'frmUpload','novalidate' => 'novalidate','onsubmit' => 'return false;']) }}        
<div class="one_half">	
	<div class="contenttitle">
		<h2 class="form"><span>Data Uploading ( Turn Out )</span></h2>
	</div>	   
    <p>		
        <label>Option</label>
        <span class="field">            
            <select name="option" id="option">
                <option value="1">Turn Out</option>
            </select>
            <small class="desc" style="margin:0px;">                
                <br>Data Type                
            </small>            
        </span>
    </p>
    <p>		
        <label>Year</label>
        <span class="field">            
            <select name="year" id="year">
                <option>2018</option>
                <option>2017</option>
            </select>
            <small class="desc" style="margin:0px;">                
                <br>Data Type                
            </small>            
        </span>
    </p>
    <p>		
        <label>Period</label>
        <span class="field">            
            <select name="period" id="period">
                <option>1</option>
                <option>2</option>
                <option>3</option>
                <option>4</option>
                <option>5</option>
                <option>6</option>                
            </select>
            <small class="desc" style="margin:0px;">                
                <br>Data Type                
            </small>            
        </span>
    </p>
    <p>		
        <label>'.csv' File format</label>
        <span class="field">            
            <input type="file" id="multipleCompressFileTurnout" name="multipleCompressFileTurnout[]" multiple>
            <small class="desc" style="margin:0px;">
                <strong>Note: </strong> The file you want to upload must be less than the size of the server configuration
                <br>Upload Maximum Size = @php  echo ini_get('upload_max_filesize') @endphp 
                <br>Maximum Files to Upload = @php  echo ini_get('max_file_uploads') @endphp <br><br>                
            </small>
            <small id="showError" style="display:none;color:#ff0000;"></small>
        </span>
    </p>
    <p class="stdformbutton">    
        <button type="button" class="stdbtn btn_black" id="btnUpload">Upload File</button>              
    	<span id="loading" style="display:inline;float:right;display:none;"><img src="images/loaders/loading_bars.gif"></span>            
    </p>

</div>

<div class="one_half last">
    <div class="widgetbox" >
        <div class="title"><h2 class="tabbed"><span>Results</span></h2></div>
        <div class="widgetcontent padding0">                
             <iframe name="displayOutput" id="displayOutput" style="min-height: 485px;border: 1px #000 dashed; overflow-y: scroll; width: 100%;" ></iframe>
        </div><!--widgetcontent-->         
    </div>
</div>    
{{ Form::close() }}
<br clear="all">
@endsection