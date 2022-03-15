<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <title>Retention Curves</title>
</head>
<body>
    <div id="completionChart"></div>
    <h2></h2>
    <div id="upcasechart"></div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://rawgit.com/mholt/PapaParse/master/papaparse.js"></script>

<script type="text/javascript">
$(document).ready(function() {
function completionData(data) 
{
    var weeks =  $.map(data[0].x, function(value, index) {return [value[0]];});
    var curve =  $.map(data[0].y, function(value, index) {return [value[0]];});
    var title ="Weekly Retention curves - Mixpanel Data";
    const chart1 = Highcharts.chart('completionChart', {
            chart: {
                type: 'line'
            },
            title: {
                text: title.toUpperCase()
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: weeks
            },
            yAxis: {
                title: {
                    text: 'Completion'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            lang :{
                noData : "No data available."
            },
            series: [{
                name: 'Weeks',
                data:  curve
            }]
        });
}


let _token   = $('meta[name="csrf-token"]').attr('content');
 $.ajax({
    url: '{{url("/api/charts/weeklyData")}}',
    type: 'GET',
    data:{
         _token: _token
     },
    async: true,
    dataType: "json",
    success: function (data) {
        completionData(data);
    }
  });

function upcaseData(data){
    var weeks =  $.map(data[0].x, function(value, index) {return [value[0]];});
    var curve =  $.map(data[0].y, function(value, index) {return [value[0]];});
    var title ="Upcase retention curve";
    const chart2 = Highcharts.chart('upcasechart', {
            chart: {
                type: 'line'
            },
            title: {
                text: title.toUpperCase()
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: weeks
            },
            yAxis: {
                title: {
                    text: 'User Engagement'
                }
            },
            plotOptions: {
                line: {
                    dataLabels: {
                        enabled: true
                    },
                    enableMouseTracking: false
                }
            },
            lang :{
                noData : "No data available."
            },
            series: [{
                name: 'Weeks',
                data:  curve
            }]
        });

}
  $.ajax({
    url: '{{url("/api/charts/upcaseData")}}',
    type: 'GET',
    data:{
         _token: _token
     },
    async: true,
    dataType: "json",
    success: function (data) {
        upcaseData(data);
    }
  });


 });

</script>

</html>