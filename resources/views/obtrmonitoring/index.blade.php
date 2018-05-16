@extends('layouts.main',[
    'header' => [
        
        ['url' => '#','title'  => 'Import OBTR Progress Report File','selected' => 'current']
    ]
])
@section('title','Upload OBTR Progress Report File','selected')
@section('optLayout','noright')

@section('cssExtention')
<link rel="stylesheet" href="js/modal/jquery.modal.min.css" type="text/css" media="screen" />
@endsection

@section('jsExtention')
<script type="text/javascript" src="{{ asset('js/plugins/jquery.jgrowl.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/modal/jquery.modal.min.js') }}"></script>


<script type="text/javascript">
 
    
</script>
@endsection

@section('content')
    
{{ Form::open(['route' => 'obtrfileupload.uploadfile','class' => 'stdform stdform2','id' => 'frmUpload','novalidate' => 'novalidate','onsubmit' => 'return false;']) }}        
<div class="one_half">	
	<div class="contenttitle">
		<h2 class="form"><span>Data Uploading ( OBTR Progress Report)</span></h2>
	</div>	   
    
    <p>		
        <label>'.xlsx' File format</label>
        <span class="field">            
           <!-- <input type="file" id="multipleCompressFileTurnout" name="multipleCompressFileTurnout[]" multiple>-->
				<input type="file" id="obtrfile" name="obtrfile[]" multiple>           
		   <small class="desc" style="margin:0px;">
                <strong>Note: </strong> The file you want to upload must not exceed the size of the server configuration...see details below.
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
        <div class="title"><h2 class="tabbed"><span>Uploading Progress</span></h2></div>
        <div class="widgetcontent padding0">                
             <iframe name="displayOutput" id="displayOutput" style="min-height: 200px;border: 1px #000 dashed; overflow-y: scroll; width: 100%;" ></iframe>
        </div><!--widgetcontent-->         
    </div>
</div>       
{{ Form::close() }}
<br clear="all">
@endsection