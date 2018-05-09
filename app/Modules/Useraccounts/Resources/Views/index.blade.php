@extends('layouts.main')

@section('title', 'User Accounts')
@section('optLayout','noright')

@section('cssExtention')

@endsection

@section('jsExtention')

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
                    <th class="head0">Access</th>
                    <th class="head1">Username</th>
                    <th class="head0">Lastname</th>
                    <th class="head1">Firstname</th>
                    <th class="head0">Middlename</th>
                    <th class="head1">Status</th>
                    <th class="head0" width="200">Option</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="head1">#</th>
                    <th class="head0">Access</th>
                    <th class="head1">Username</th>
                    <th class="head0">Lastname</th>
                    <th class="head1">Firstname</th>
                    <th class="head0">Middlename</th>
                    <th class="head1">Status</th>
                    <th class="head0">Option</th>
                </tr>
            </tfoot>
            <tbody>
                @php ($i=1)
                @foreach($users as $r)            
                <tr id="row_{{ $r->id }}">
                    <td>{{ $i }}</td>
                    <td>{{ $r->permission }}</td>
                    <td>{{ $r->username }}</td>
                    <td>{{ $r->lname }}</td>
                    <td>{{ $r->fname }}</td>
                    <td>{{ $r->mname }}</td>
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

@section('right')

@endsection
