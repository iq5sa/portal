@extends('admin.layouts.app')
@section('styles')
@endsection
@section('content')
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> اللوحة الرئيسية</h1>
            <p>نظام أدارة شؤون الطلبة.</p>
        </div>
    </div>
    <div class="row">
        {{--<div class="col-sm-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
        <div class="col-md-12">
            <div class="jumbotron jumbotron-fluid shadow-sm">
                <div class="container">
                    <h1 class="display-4">مرحبا بك</h1>
                    <p class="lead"> </p>
                </div>
            </div>
        </div>--}}
    </div>
    {{--<div class="row">
        <div class="col-md-6 col-lg-3">
            <div class="widget-small warning coloured-icon"><i class="icon fa fa-wpforms fa-3x"></i>
                <div class="info">
                    <h4>عدد الاستمارات</h4>
                    <p><b>{{$forms_number}}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                <div class="info">
                    <h4>المستخدمين</h4>
                    <p><b>{{$users_number}}</b></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="widget-small info coloured-icon"><i class="icon fa fa-cubes fa-3x"></i>
                <div class="info">
                    <h4>الدرجات المعلنه</h4>
                    <p><b>{{$job_types_number}}</b></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="widget-small danger coloured-icon"><i class="icon fa fa-star fa-3x"></i>
                <div class="info">
                    <h4>المتقدمين</h4>
                    <p><b>{{$requests_number}}</b></p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="tile">
                <h5 class="mb-1">أحصائية أعداد المتقدمين حسب اختصاص المتقدم</h5>
                <hr>
                <p class="mb-1 text-center">{{$active_cat}}</p>
                <div class="embed-responsive embed-responsive-16by9">
                    <canvas class="embed-responsive-item" id="lineChartDemo" width="475" height="267"
                            style="width: 475px; height: 267px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="tile">
                <h5 class="mb-1">أحصائية أعداد المتقدمين حسب شهادة المتقدم</h5>
                <hr>
                <p class="mb-1 text-center">{{$active_cat}}</p>
                <div class="embed-responsive embed-responsive-16by9">
                    <canvas class="embed-responsive-item" id="pieChartDemo" width="475" height="267"
                            style="width: 475px; height: 267px;"></canvas>
                </div>
            </div>
        </div>
    </div>--}}

@endsection
{{--@section('scripts')
    <script type="text/javascript" src="{{asset('js/plugins/Chart.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            /*var data = {
             labels: ["January", "February", "March", "April", "May"],
             datasets: [
                 {
                     label: "My First dataset",
                     fillColor: "rgba(220,220,220,0.2)",
                     strokeColor: "rgba(220,220,220,1)",
                     pointColor: "rgba(220,220,220,1)",
                     pointStrokeColor: "#fff",
                     pointHighlightFill: "#fff",
                     pointHighlightStroke: "rgba(220,220,220,1)",
                     data: [65, 59, 80, 81, 56]
                 },
                 {
                     label: "My Second dataset",
                     fillColor: "rgba(151,187,205,0.2)",
                     strokeColor: "rgba(151,187,205,1)",
                     pointColor: "rgba(151,187,205,1)",
                     pointStrokeColor: "#fff",
                     pointHighlightFill: "#fff",
                     pointHighlightStroke: "rgba(151,187,205,1)",
                     data: [28, 48, 40, 19, 86]
                 }
             ]
         };
         var pdata = [
             {
                 value: 300,
                 color: "#46BFBD",
                 highlight: "#5AD3D1",
                 label: "Complete"
             },
             {
                 value: 50,
                 color:"#F7464A",
                 highlight: "#FF5A5E",
                 label: "In-Progress"
             }
         ]

         var ctxl = $("#lineChartDemo").get(0).getContext("2d");
         var lineChart = new Chart(ctxl).Line(data);

         var ctxp = $("#pieChartDemo").get(0).getContext("2d");
         var pieChart = new Chart(ctxp).Pie(pdata);*/

            window.chartColors = {
                red: 'rgb(255, 99, 132)',
                orange: 'rgb(255, 159, 64)',
                yellow: 'rgb(255, 205, 86)',
                green: 'rgb(75, 192, 192)',
                blue: 'rgb(54, 162, 235)',
                purple: 'rgb(153, 102, 255)',
                grey: 'rgb(201, 203, 207)'
            };


            axios.get('{{route("statistics.jobTypes")}}')
                .then(function (response) {
                    var coloR = [];

                    var jsondata = response.data;
                    console.log(jsondata);

                    var labels = jsondata.map(function (e) {
                        return e.spec;
                    });
                    var data = jsondata.map(function (e) {
                        return e.number;
                    });

                    var dynamicColors = function() {
                        var r = Math.floor(Math.random() * 255);
                        var g = Math.floor(Math.random() * 255);
                        var b = Math.floor(Math.random() * 255);
                        var a = 1;
                        return "rgb(" + r + "," + g + "," + b + "," + a + ")";
                    };

                    for (var i in data) {
                        coloR.push(dynamicColors());
                    }


                    var ctx = document.getElementById('lineChartDemo').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: data,
                                backgroundColor: coloR,
                                borderColor: 'rgba(0,0,0, 1)',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            legend: {
                                display: false
                            },
                            title: {
                                display: true,
                                text: 'عدد المتقدمين حسب أختصاص المتقدم',
                                fontSize:16
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero: true,
                                    }
                                }],
                                xAxes: [{
                                    gridLines: {
                                        offsetGridLines: true
                                    }
                                }]
                            }

                        }
                    });
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {

                });
            axios.get('{{route("statistics.certificate")}}')
                .then(function (response) {
                    var jsondata = response.data;
                    var labels = jsondata.map(function (e) {
                        return e.certificate;
                    });
                    var data = jsondata.map(function (e) {
                        return e.number;
                    });

                    var ctx = document.getElementById('pieChartDemo').getContext('2d');
                    var myChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data: data,
                                backgroundColor: [
                                    window.chartColors.red,
                                    window.chartColors.orange,
                                    window.chartColors.green,
                                    window.chartColors.blue,
                                ],
                            }],

                            // These labels appear in the legend and in the tooltips when hovering different arcs
                            labels: labels
                        },
                        options: {
                            legend: {
                                display: true
                            },
                            title: {
                                display: true,
                                text: 'عدد المتقدمين حسب شهادة المتقدم',
                                fontSize:16
                            },
                        }
                    });
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                })
                .finally(function () {

                });
        });


    </script>
@endsection--}}
