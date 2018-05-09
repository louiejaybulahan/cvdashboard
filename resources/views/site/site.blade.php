@extends('layouts.main')

@section('title', 'Dashboard')
@section('optLayout','noright')

@section('cssExtention')
<!-- link rel="stylesheet" href="js/datatables/datatables.min.css" type="text/css" media="screen" / -->
<link rel="stylesheet" href="js/highcharts.5.0.14/code/css/highcharts.css" type="text/css" media="screen" />
@endsection

@section('jsExtention')
<!-- script type="text/javascript" src="{{ asset('js/plugins/jquery.flot.min.js') }}"></script -->
<!-- script type="text/javascript" src="{{ asset('js/plugins/jquery.flot.resize.min.js') }}"></script -->
<!-- script type="text/javascript" src="{{ asset('js/custom/dashboard.js') }}"></script -->
<script type="text/javascript" src="{{ asset('js/highcharts.5.0.14/code/highcharts.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/highcharts.5.0.14/code/highcharts-3d.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/highcharts.5.0.14/code/modules/exporting.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/datatables/datatables.min.js') }}"></script>
@endsection

@section('content')																			

@endsection

@section('right')

@endsection
