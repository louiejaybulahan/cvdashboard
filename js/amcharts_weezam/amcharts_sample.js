
jQuery(window).ready(function () { 

amcharts_sample1();
amcharts_sample2();
});

function amcharts_sample1(){
var chart = AmCharts.makeChart("chartdiv", {
	"type": "serial",
     "theme": "light",
	"categoryField": "year",
	"rotate": true,
	"startDuration": 1,
	"categoryAxis": {
		"gridPosition": "start",
		"position": "left"
	},
	"trendLines": [],
	"graphs": [
		{
			"balloonText": "Compliant+State of Calamity VS Eligible:[[value]]",
			"fillAlphas": 0.8,
			"id": "AmGraph-1",
			"lineAlpha": 0.2,
			"title": "eligible",
			"type": "column",
			"valueField": "eligible"
		},
		{
			"balloonText": "Compliant VS Submitted:[[value]]",
			"fillAlphas": 0.8,
			"id": "AmGraph-2",
			"lineAlpha": 0.2,
			"title": "submitted",
			"type": "column",
			"valueField": "submitted"
		}
	],
	"guides": [],
	"valueAxes": [
		{
			"id": "ValueAxis-1",
			"position": "top",
			"axisAlpha": 0
		}
	],
	"allLabels": [],
	"balloon": {},
	"titles": [],
	"dataProvider": [
		{
			"year": "ARMM",
			"eligible": 93.5,
			"submitted": 89.1
		},
		{
			"year": "CARAGA",
			"eligible": 87.2,
			"submitted": 69.8
		},
		{
			"year": "CAR",
			"eligible": 94.1,
			"submitted": 65.9
		},
		{
			"year": "I",
			"eligible": 87.5,
			"submitted": 98.1
		},
		{
			"year": "II",
			"eligible": 49.6,
			"submitted": 75
		},
		{
			"year": "III",
			"eligible": 93.5,
			"submitted": 89.1
		},
		{
			"year": "IV-B",
			"eligible": 87.2,
			"submitted": 69.8
		},
		{
			"year": "IX",
			"eligible": 97.1,
			"submitted": 65.9
		},
		{
			"year": "NCR",
			"eligible": 88.5,
			"submitted": 98.1
		},
		{
			"year": "NIR",
			"eligible": 56.6,
			"submitted": 75
		}
	],
    "export": {
    	"enabled": true
     }

});
}

function amcharts_sample2(){
	
	var chart = AmCharts.makeChart("chartdiv3d", {
    "theme": "light",
    "type": "serial",
	"startDuration": 2,
    "dataProvider": [{
        "country": "ARMM",
        "visits": 4025,
        "color": "#FF0F00"
    }, {
        "country": "CAR",
        "visits": 1882,
        "color": "#FF6600"
    }, {
        "country": "CARAGA",
        "visits": 1809,
        "color": "#FF9E01"
    }, {
        "country": "I",
        "visits": 1322,
        "color": "#FCD202"
    }, {
        "country": "II",
        "visits": 1122,
        "color": "#F8FF01"
    }, {
        "country": "III",
        "visits": 1114,
        "color": "#B0DE09"
    }, {
        "country": "IV-B",
        "visits": 984,
        "color": "#04D215"
    }, {
        "country": "IX",
        "visits": 711,
        "color": "#0D8ECF"
    }, {
        "country": "NCR",
        "visits": 665,
        "color": "#0D52D1"
    }, {
        "country": "NIR",
        "visits": 580,
        "color": "#2A0CD0"
    }, {
        "country": "V",
        "visits": 443,
        "color": "#8A0CCF"
    }, {
        "country": "VI",
        "visits": 441,
        "color": "#CD0D74"
    }, {
        "country": "VII",
        "visits": 395,
        "color": "#754DEB"
    }, {
        "country": "VIII",
        "visits": 386,
        "color": "#DDDDDD"
    }, {
        "country": "X",
        "visits": 384,
        "color": "#999999"
    }, {
        "country": "XI",
        "visits": 338,
        "color": "#333333"
    }, {
        "country": "XII",
        "visits": 328,
        "color": "#000000"
    }],
    "valueAxes": [{
        "position": "left",
        "title": "DATA COUNTS"
    }],
    "graphs": [{
        "balloonText": "[[category]]: <b>[[value]]</b>",
        "fillColorsField": "color",
        "fillAlphas": 1,
        "lineAlpha": 0.1,
        "type": "column",
        "valueField": "visits"
    }],
    "depth3D": 20,
	"angle": 30,
    "chartCursor": {
        "categoryBalloonEnabled": false,
        "cursorAlpha": 0,
        "zoomable": false
    },
    "categoryField": "country",
    "categoryAxis": {
        "gridPosition": "start",
        "labelRotation": 90
    },
    "export": {
    	"enabled": true
     }

});
}