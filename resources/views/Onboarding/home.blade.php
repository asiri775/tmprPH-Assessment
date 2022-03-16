<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>Retention Curves</title>
</head>
<style>
.info,
.success,
.warning,
.error,
.validation {
	border: 1px solid;
	margin: 10px auto;
	padding: 15px 10px 15px 50px;
	background-repeat: no-repeat;
	background-position: 10px center;
	max-width: 460px;
}
</style>
<body>
    <div id="message" class="error"></div>
    <div id="completionChart"></div>
    <h2></h2>
    <div id="upcasechart"></div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://rawgit.com/mholt/PapaParse/master/papaparse.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        function completionData(data) {
            var weeks = $.map(data[0].x, function(value, index) {
                return [value[0]];
            });
            var curve = $.map(data[0].y, function(value, index) {
                return [value[0]];
            });
            var title = "Weekly Retention curves - Mixpanel Data";
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
                lang: {
                    loading: "No data available."
                },
                series: [{
                    name: 'Weeks',
                    data: curve
                }]
            });

        }

        function upcaseData(data) {
            var weeks = $.map(data[0].x, function(value, index) {
                return [value[0]];
            });
            var curve = $.map(data[0].y, function(value, index) {
                return [value[0]];
            });
            var title = "Upcase retention curve";
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
                lang: {
                    loading: "No data available."
                },
                series: [{
                    name: 'Weeks',
                    data: curve
                }]
            });

        }
        let _token = $('meta[name="csrf-token"]').attr('content');
        $('#message').hide();
        $.ajax({
            url: '{{ url('/api/charts/dataValidation') }}',
            type: 'GET',
            data: {
                _token: _token
            },
            async: true,
            dataType: "json",
            success: function(data) {
                if (data.status) {
                    $.ajax({
                        url: '{{ url('/api/charts/weeklyData') }}',
                        type: 'GET',
                        data: {
                            _token: _token
                        },
                        async: true,
                        dataType: "json",
                        success: function(data) {
                            completionData(data);
                        }
                    });

                    $.ajax({
                        url: '{{ url('/api/charts/upcaseData') }}',
                        type: 'GET',
                        data: {
                            _token: _token
                        },
                        async: true,
                        dataType: "json",
                        success: function(data) {
                            upcaseData(data);
                        }
                    });
                    $('#message').hide();
                }
                else {
                    $('#message').show();
                    $('#message').html(data.message);
                }
            }
        });





    });
</script>

</html>
