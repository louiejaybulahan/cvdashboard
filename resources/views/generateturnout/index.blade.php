@extends('layouts.main',[
    'header' => [
        ['url' => '#','title' => 'Generate Turnout','selected' => 'current'],
        ['url' => route('periodactive.index'),'title'  => 'Set Active Period','selected' => '']
    ]
])
@section('title', 'Generate Turnout')
@section('optLayout','noright')

@section('cssExtention')
<link rel="stylesheet" href="js/modal/jquery.modal.min.css" type="text/css" media="screen" />
<link rel="stylesheet" href="js/jquery-confirm/css/jquery-confirm.css" type="text/css" media="screen" />
@endsection

@section('jsExtention')
<script type="text/javascript" src="{{ asset('js/plugins/jquery.jgrowl.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/modal/jquery.modal.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-confirm/js/jquery-confirm.js') }}"></script>
<script type="text/javascript">
function generateturnout(region,year,period){
    $.confirm({
        title: 'Generate turnout?',
        content: 'This will generate compliance verification turnout with the details below.<br><br>REGION&nbsp;: '+region+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;YEAR&nbsp;: '+year+'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PERIOD&nbsp;: '+period+'<br><br>Do you want to continue?',
        draggable: true,
        type: 'blue',
        closeIcon: true,
        buttons: {
            confirm: function () {    
                $("#displayOutput").attr("src", $('#btngenerate_'+region+'_'+year+'_'+period).attr('set-path'));                
            },
            cancel: function () {
            }
        }
    });    
    //$('.classgenerate').prop('disabled',true);
}

</script>
@endsection

@section('content')

<div class="one_half">	
    <div class="widgetbox" style="width: 100%">
        <div class="title"><h2 class="tabbed"><span>DATA GENERATION ( FROM Turn Out )</span></h2></div>
        <div class="widgetcontent padding0 statement">
            <table cellpadding="0" cellspacing="0" border="0" class="stdtable">
                <colgroup>
                    <col class="con0">
                    <col class="con1">
                    <col class="con0">
                    <col class="con0">
                </colgroup>
                <thead>
                    <tr>
                        <th class="head0">Region</th>
                        <th class="head1">Year</th>
                        <th class="head0">Period</th>
                        <th class="head1">Generate</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($region as $reg)
                        <tr>
                            <td>{{$reg->REGION_NICK}}</td>
                            <td>{{$periodActive->year}}</td>
                            <td>{{$periodActive->period}}</td>
                            <td><button class="btn classgenerate" id="btngenerate_{{$reg->REGION_NICK.'_'.$periodActive->year.'_'.$periodActive->period}}" href='javascript://' onclick="generateturnout('{{$reg->REGION_NICK}}',{{$periodActive->year}},{{$periodActive->period}})" set-path="{{ route('generateturnout.generate',['region'=>$reg->REGION_NICK, 'year'=>$periodActive->year,'period'=>$periodActive->period]) }}">-</button></td>
                        </tr>
                    @endforeach
                    

                </tbody>
            </table>
        </div><!--widgetcontent-->
    </div>
<div class="notification msgalert"><a class="close"></a><p>You can change year and period by changing it in SET ACTIVE PERIOD tab.</p></div>


</div>

<div class="one_half last">
    <div class="widgetbox" >
        <div class="title"><h2 class="tabbed"><span>Results</span></h2></div>
        <div class="widgetcontent padding0">                
            <iframe name="displayOutput" id="displayOutput" style="min-height: 719px;overflow-y: scroll; width: 100%;" ></iframe>
        </div><!--widgetcontent-->         
    </div>
</div>    
<br clear="all">

@endsection 

