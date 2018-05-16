@extends('layouts.main',[
    'header' => [
        ['url' => route('generateturnout.index'),'title'  => 'Generate Turnout','selected' => ''],
        ['url' => '#','title' => 'Set Active Period','selected' => 'current'],
        
        
    ]
])
@section('title', 'Generate Turnout')
@section('optLayout','noright')

@section('cssExtention')
<link rel="stylesheet" href="js/modal/jquery.modal.min.css" type="text/css" media="screen" />
@endsection

@section('jsExtention')
<script type="text/javascript" src="{{ asset('js/plugins/jquery.jgrowl.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/modal/jquery.modal.min.js') }}"></script>

@endsection

@section('content')
<div class="one_half">	
    <div class="widgetbox" style="width: 100%">
        <div class="title"><h2 class="tabbed"><span>DATA GENERATION ( FROM Turn Out )</span></h2></div>
        <div class="widgetcontent padding0 statement">
            <table cellpadding="0" cellspacing="0" border="0" class="stdtable">
                <colgroup>
                    <col class="con1">
                    <col class="con0">
                    <col class="con1">
                    <col class="con0">
                </colgroup>
                <thead>
                    <tr>

                        <th class="head1">Year</th>
                        <th class="head0">Period</th>
                        <th class="head1">Status</th>
                        <th class="head0">Generate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($period as $per)
                        <tr>
                            <td>{{$per->year}}</td>
                            <td>{{$per->period}}</td>
                            <td>
                               @if($per->is_status==1) 
                                <font color="green"><b>Active</b></font>
                               @endif
                            <td>
                                @if($per->is_status==0)
                                <a href=" {{ route('periodactive.index',['period_id'=>$per->period_id]) }}">Set to Active</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
            
        </div><!--widgetcontent-->
        
    </div>



</div>

<div class="one_half last">
    <div class="widgetbox" >
        <div class="title"><h2 class="tabbed"><span>Results</span></h2></div>
        <div class="widgetcontent padding0">                
            <iframe name="displayOutput" id="displayOutput" style="min-height: 485px;overflow-y: scroll; width: 100%;" ></iframe>
        </div><!--widgetcontent-->         
    </div>
</div>    
<br clear="all">

@endsection 


