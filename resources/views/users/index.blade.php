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
/*
jQuery(document).ready(function () {
    jQuery('#dyntable').dataTable({
        'sPaginationType': 'full_numbers'
    });
    jQuery('#dyntable_length').prepend('<button class="stdbtn btn_blue" id="btnNew">+ New User</button> ');
    jQuery('.btnBack').click(function () {
        jQuery('#divForm').hide();
        jQuery('#divPermission').hide();
        jQuery('#divList').fadeIn();
    });
    jQuery('#btnNew').click(function () {
        jQuery('#divList').hide();
        jQuery('#divForm').fadeIn();        
        jQuery("#id,#userid,#lname,#fname,#mname,#password,#retype,#username,#oldusername").val('');
    });
    jQuery('#btnSubmitForm').click(function () {
        var message = '#divUserMessage';
        var form = '#formUser';
        jQuery.ajax({
            type: "POST", url: jQuery(form).attr('action'), data: jQuery(form).serialize(), dataType: 'json', cache: false,
            error: function (request, status, error) { jsMessage('Error Request'); },
            success: function (data) {
                if (data.flag == 1){
                    jsMessage(data.msg);
                    window.setTimeout(location.reload(), 10000);
                }
                else{
                    var errorMessage = '';
                    if (data.msg != '' && jQuery.isEmptyObject(data.msg) == false) { jQuery.each(data.msg, function(key, val){ errorMessage += ' * ' + val + ', '; }); }
                    jQuery(message).html('<a class="close"></a><p>' + errorMessage + '</p>').fadeIn();
                }
            }
        });    
    });
    jQuery('.chosen,#municipal').chosen({width: "95%"});
    jQuery('#province').change(function(){
        var tmpMunicipal = new Array();  
        var select = '#municipal';
        var dataString = {'_token':'{{ csrf_token() }}', 'id':jQuery(this).val()};
        jQuery.ajax({
            type: "POST", url:' route('users.municipal') ', data: dataString, dataType: 'json', cache: false,
            error: function (request, status, error) { jsMessage('Error Request'); },
            success: function (data) {
                tmpMunicipal = $(select).val();                                
                $(select+' option').remove();                
                if (data.list != '' && jQuery.isEmptyObject(data.list) == false) { 
                    jQuery.each(data.list, function(key, val){                         
                        $(select).append('<option value="'+val.prov_id+'-'+val.mun_id+'">'+val.name+'</option>');
                    }); 
                    if(tmpMunicipal!=null){                                                
                        $(select).val(tmpMunicipal);
                    }                    
                }
                $(select).trigger('chosen:updated');
            }
        });   
    });    
});
function jsMessage(message) { jQuery.jGrowl(message); return false; }
function jsEdit(id){
    var dataString = {'_token':'{{ csrf_token() }}', 'id':id};
    jQuery.ajax({
    type: "POST", url: 'route('users.edit') ', data: dataString, dataType: 'json', cache: false,
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
    type: "POST", url: 'route('users.remove')', data: dataString, dataType: 'json', cache: false,
            error: function (request, status, error) { jsMessage('Error Request'); },
            success: function (data) {
                jsMessage(data.msg);
                if (data.flag == 1){ jQuery('#row_' + id).remove(); }                
            }
    });
    return false;
}
function jsPermission(id,username,stat){
    var dataString = {'_token':'{{ csrf_token() }}', 'id':id,'username':username,};
    jQuery('#divList').hide();
    jQuery('#divPermission').fadeIn();
    jQuery.ajax({
    type: "POST", url: ' route('users.permission') ', data: dataString, dataType: 'json', cache: false,
            error: function (request, status, error) { jsMessage('Error Request'); },
            success: function (data) {   
                jQuery('#permissionUsername').val(username);
                jQuery('#permissionId').val(id);
                jQuery('input[name=user_status][value='+stat+']').prop('checked', 'checked');
                if (data.flag == 1){                                        
                    jQuery('input[name=permission][value='+data.roles.permission+']').prop('checked', 'checked');
                }
                jQuery('#province').val(data.listProvince);
                jQuery('#province').trigger('chosen:updated').trigger('change');
                var tmpMunicipal = new Array();
                if (data.area != '' && jQuery.isEmptyObject(data.area) == false) { 
                    jQuery.each(data.area, function(key, val){
                        tmpMunicipal.push(val.province+'-'+val.mun);
                    });
                }
                jQuery('#municipal option').remove();
                if (data.municipal != '' && jQuery.isEmptyObject(data.municipal) == false) {
                    jQuery.each(data.municipal, function(key, val){
                        $('#municipal').append('<option value="'+val.prov_id+'-'+val.mun_id+'">'+val.name+'</option>');
                    }); 
                    if(tmpMunicipal!=null){
                        $('#municipal').val(tmpMunicipal);
                    }
                }
                $('#municipal').trigger('chosen:updated');
            }
    });
    return false;
}
function savePermissions(){       
    jQuery.ajax({
        type: "POST", url: 'route('users.savepermission') ', data: jQuery('#formPermission').serialize(), dataType: 'json', cache: false,
        error: function (request, status, error) { jsMessage('Error Request'); },
        success: function (data) {
            if (data.flag == 1){  
                jsMessage('Successfully Save'); 
            } else{ jsMessage('Error Request. Invalid Username'); } 
        }
    });
    return false;    
}
*/

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


<br clear="all">

@endsection