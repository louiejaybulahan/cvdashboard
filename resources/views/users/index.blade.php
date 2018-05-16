@extends('layouts.main')

@section('title', 'List of Users')
@section('optLayout','noright')

@section('cssExtention')
<link rel="stylesheet" href="js/modal/jquery.modal.min.css" type="text/css" media="screen" />
<link rel="stylesheet" href="js/chosen/chosen.css" type="text/css" media="screen" />
@endsection

@section('jsExtention')
<!-- script type="text/javascript" src="{{ asset('js/custom/dashboard.js') }}"></script -->
<script type="text/javascript" src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jquery.jgrowl.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/modal/jquery.modal.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/chosen/chosen.jquery.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/chosen/init.js') }}"></script>

<script type="text/javascript">
jQuery(document).ready(function () {
    // jQuery('#frmSubmitForm input[name="id"]').val('hello world');
    jQuery('.chosen').chosen({width: "95%"});
    jQuery('#dyntable').dataTable({'sPaginationType': 'full_numbers'});
    jQuery('#dyntable_length').prepend('<a href="#addnewUser" id="btnNewAccount" class="stdbtn btn_blue"><span>New Account</span></a> ');
    jQuery('.btnBack').click(function () {
        jQuery('#divAccount').hide();        
        jQuery('#divList').fadeIn();
    });
    jQuery('#btnNewAccount').click(function(){
        $('#divList').hide();
        $('#divAccount').fadeIn();
    });

    jQuery('#btnAccountSave').click(function(){
        jQuery(this).attr('class','btn btn-default');        
        jQuery(this).attr('class','stdbtn btn_blue').prop('disabled',true);
        var dataString = $('#frmSubmitForm').serialize();
        $('#divListError').fadeOut();
        jQuery.ajax({
            type: "POST", url: "{{ route('users.save') }}", data: dataString, dataType: 'json', cache: false,
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
                }                 
            }
        }).done(function(){
            jQuery('#btnAccountSave').attr('class','stdbtn btn_black').prop('disabled',false);            
        });
    });
});
function jsMessage(message) { jQuery.jGrowl(message); return false; }
function jsEdit(id){
    var dataString = {'_token':'{{ csrf_token() }}', 'id':id};
    jQuery.ajax({
        type: "POST", url: '', data: dataString, dataType: 'json', cache: false,
        error: function (request, status, error) { jsMessage('Error Request'); },
        success: function (data) {
            if (data.flag == 1){
            var usr = data.msg;
                    jQuery('#btnNew').trigger('click');
                    jQuery('#userid').val(usr.id);
                    jQuery('#oldusername').val(usr.username);
                    jQuery('#username').val(usr.username);
                    jQuery('#lname').val(usr.lname);
                    jQuery('#fname').val(usr.fname);
                    jQuery('#mname').val(usr.mname);
            } 
            else{ jsMessage('Error Request. Invalid Username'); }
        }
    });
    return false;
}
function jsDelete(id){
    var dataString = {'_token':'{{ csrf_token() }}', 'id':id};
    jQuery.ajax({
    type: "POST", url: "{{ route('users.remove') }}", data: dataString, dataType: 'json', cache: false,
            error: function (request, status, error) { jsMessage('Error Request'); },
            success: function (data) {
                jsMessage(data.msg);
                if (data.flag == 1){ jQuery('#row_' + id).remove(); }                
            }
    });
    return false;
}
</script>
@endsection

@section('content')	
<div id="divList">
    <div class="contenttitle radiusbottom0"><h2 class="table"><span>List</span></h2></div>
    <table cellpadding="0" cellspacing="0" border="0" class="stdtable" id="dyntable">
        <colgroup>
            <col class="con1" />
            <col class="con0" />
            <col class="con1" />
            <col class="con0" />
            <col class="con1" />
            <col class="con0" />
            <col class="con1" />
        </colgroup>
        <thead>
            <tr>
                <th class="head1" width="10">#</th>               
                <th class="head0">Username</th>
                <th class="head1">Lastname</th>
                <th class="head0">Firstname</th>
                <th class="head1">Middlename</th>
                <th class="head0">Status</th>
                <th class="head1" width="200">Option</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th class="head1">#</th>                
                <th class="head0">Username</th>
                <th class="head1">Lastname</th>
                <th class="head0">Firstname</th>
                <th class="head1">Middlename</th>
                <th class="head0">Status</th>
                <th class="head1">Option</th>
            </tr>
        </tfoot>
        <tbody>
            @php ($i=1)
            @foreach($users as $r)            
            <tr id="row_{{ $r->id }}">
                <td>{{ $i }}</td>
                <td>{{ $r->username }}</td>
                <td>{{ $r->lastname }}</td>
                <td>{{ $r->firstname }}</td>
                <td>{{ $r->middlename }}</td>
                <td>{{ $r->status ? 'Active' : 'Block' }}</td>
                <td>
                    <a href="#Edit-{{ $r->id }}" onclick="jsEdit('{{ $r->id }}');">ModifyAccount</a> | 
                    <a href="#Permission-{{ $r->id }}" onclick="jsPermission('{{ $r->id }}','{{ $r->username }}','{{ $r->status }}');">Permission</a> | 
                    <a href="#Delete-{{ $r->id }}" onclick="jsDelete('{{ $r->id }}');">Delete</a></td>
                @php ($i++)
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div id="divAccount" style="display:none;">       
    <div id="divListError" style="color:red;display:none;"></div> <br>
    <div>
    {{ Form::open(['route' => 'users.save','class' => 'stdform','id' => 'frmSubmitForm','novalidate' => 'novalidate','onsubmit' => 'return false;']) }}     
        <input type="hidden" id="id" name="id" value="">
        <div class="contenttitle">
            <h2 class="button"><span>Login Information</span></h2>
        </div>
        <br>
        <p>
            <label>Username</label>
            <span class="field"><input type="text" name="username" id="username" class="longinput"><small class="desc" style="margin:0px;padding-bottom:10px;">Require field</small></span>
            
        </p> 
        <p>
            <label>Password</label>
            <span class="field"><input type="password" name="password" id="password" class="longinput"><small class="desc" style="margin:0px;padding-bottom:10px;">Require field</small></span>
            
        </p> 
        <p>
            <label>Confirm Password</label>
            <span class="field"><input type="password" name="retype" id="retype" class="longinput"><small class="desc" style="margin:0px;padding-bottom:10px;">Require field</small></span>
            
        </p> 
        <div class="contenttitle">
            <h2 class="button"><span>Access Level</span></h2>
        </div><br>
        <p>
            <label>User level</label>
            <span class="field">
                <select name="userlevel" id="userlevel" class="chosen">
                    <option value="">Choose One</option>
                    @foreach($userlevel AS $r )
                        <option value="{{ $r->level_id }}">{{ $r->level_name }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;padding-bottom:10px;">Require field</small>
            </span>
        </p>   
        <p>
            <label>Access Level Location</label>
            <span class="field">
                <select name="region" id="region" class="chosen">
                    <option value="">Choose One</option>                  
                    @foreach($region AS $r )
                        <option value="{{ $r->REGION_ID }}">{{ $r->REGION_NAME }}</option>
                    @endforeach
                </select>
                <small class="desc" style="margin:0px;padding-bottom:10px;">Require field</small>
            </span>
        </p>   
                
        <div class="contenttitle">
            <h2 class="button"><span>User Information</span></h2>
        </div>
        <br>
        <p>
            <label>First Name</label>
            <span class="field"><input type="text" name="firstname" id="firstname" class="longinput"><small class="desc" style="margin:0px;padding-bottom:10px;">Require field</small></span>
            
        </p>                        
        <p>
            <label>Last Name</label>
            <span class="field"><input type="text" name="lastname" id="lastname" class="longinput"><small class="desc" style="margin:0px;padding-bottom:10px;">Require field</small></span>
        </p>                        
        <p>
            <label>Email</label>
            <span class="field"><input type="text" name="email" id="email" class="longinput"><small class="desc" style="margin:0px;padding-bottom:10px;">Require field</small></span>
        </p>
        <p>
            <label>Contact</label>
            <span class="field"><input type="text" name="contact" id="contact" class="longinput"><small class="desc" style="margin:0px;padding-bottom:10px;">Require field</small></span>
        </p>                     
        <br>                        
        <p class="stdformbutton">
            <button class="stdbtn btnBack">Back</button>&nbsp;
            <button class="submit radius2" id="btnAccountSave">Save New Account</button>
        </p>
    {{ Form::close() }}    
</div>

<br clear="all">

@endsection