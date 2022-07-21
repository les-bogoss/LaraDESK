@extends('layouts.app')
@section('title', 'Data Dashboard - ' . config('app.name', 'Laravel'))

@section('content')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChartCategories);
        google.charts.setOnLoadCallback(drawChartStatus);
        google.charts.setOnLoadCallback(drawChartSatisfaction);
        google.charts.setOnLoadCallback(drawChartOpenCloseTickets);

        var options = {
                width: '100%',
                height: 300,
                responsive: true,
            pieHole: 0.8,
            pieSliceText: 'none',
            legend: 'labeled',
            chartArea: {
                width: '80%',
                height: '60%',
            },
            backgroundColor: {
                fill: 'transparent'
            },
            colors: ['#FFDD4A', '#7E38F3', '#C9E8FC', '#3DBD00', '#C5D0FA', '#F8D5C4']
        };

        function drawChartCategories() {
            var data = google.visualization.arrayToDataTable([
                ['Categories', 'Numbers of tickets'],
                @foreach ($ticket_categories as $category)
                    ['{{ $category->label }}', {{ $tickets->where('category_id', $category->id)->count() }}],
                @endforeach
            ]);

            var chart = new google.visualization.PieChart(document.getElementById('donutCategories'));
            options.title = "Pourcentage of tickets by categories";
            chart.draw(data, options);
        }

        function drawChartStatus() {
            var data = google.visualization.arrayToDataTable([
                ['Status', 'Numbers of tickets'],
                @foreach ($ticket_statuses as $status)
                    ['{{ $status->name }}', {{ $tickets->where('status_id', $status->id)->count() }}],
                @endforeach
            ]);

            var chart = new google.visualization.PieChart(document.getElementById('donutStatuses'));
            options.title = "Pourcentage of tickets by status";
            chart.draw(data, options);
        }

        google.charts.load('current', {
            'packages': ['line']
        });

        function drawChartOpenCloseTickets() {
            var data = new google.visualization.arrayToDataTable([
                ['Year', 'Ticket Open', 'Ticket Close'],
                @for ($i = 1; $i <= 7; $i++)
                    [
                        @php
                            $date = Carbon\Carbon::now()
                                ->subDays($i)
                                ->StartOfDay();
                            $sub_date = Carbon\Carbon::now()
                                ->subDays($i - 1)
                                ->StartOfDay();
                        @endphp
                        new Date({{ $date->year }}, {{ $date->month }}, {{ $date->day }}),
                        {{ $tickets->where('created_at', '>=', $date)->where('created_at', '<', $sub_date)->count() }},
                        {{ $tickets->where('status_id', 5)->where('updated_at', '>=', $date)->where('updated_at', '<', $sub_date)->count() }}
                    ],
                @endfor
            ]);

            var options = {
                title: 'Tickets opened and closed per day',
                colors: ['#FFDD4A', '#7E38F3', '#C9E8FC', '#3DBD00', '#C5D0FA', '#F8D5C4'],
                width: '100%',
                height: 300,
                responsive: true,
                pointsVisible: true,
                backgroundColor: {
                    fill: 'transparent'
                },
            };

            var chart = new google.visualization.AreaChart(document.getElementById('GraphOpenCloseTickets'));

            chart.draw(data, options);
        }

        function drawChartSatisfaction() {
            var data = google.visualization.arrayToDataTable([
                ["Satisfaction", "Pourcentage", {
                    role: "style"
                }],
                @php
                    $total = $tickets->where('rating', '>', '0')->count();
                @endphp
                ["Moyen", {{ round(($tickets->where('rating', 1)->count() / $total) * 100, 1) }}, "#f88e55"],
                ["Bon", {{ round(($tickets->where('rating', 2)->count() / $total) * 100, 1) }}, "#fef86c"],
                ["Excellent", {{ round(($tickets->where('rating', 3)->count() / $total) * 100, 1) }}, "#90EE90"],
            ]);

            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                {
                    calc: "stringify",
                    sourceColumn: 1,
                    type: "string",
                    role: "annotation",
                },
                2
            ]);

            var options = {
                title: "Pourcentage of satisfaction",
                width: '100%',
                height: 300,
                responsive: true,
                bar: {
                    groupWidth: "95%"
                },
                legend: {
                    position: "none"
                },
                backgroundColor: {
                    fill: 'transparent'
                },
                vAxis: {  
              viewWindowMode:'explicit',
              viewWindow:{
                max:100,
                min:0
              }
            }
            };
            var chart = new google.visualization.ColumnChart(document.getElementById("ChartSatisfaction"));
            chart.draw(view, options)
        
        }

        window.onresize = function () {
            drawChartCategories();
            drawChartStatus();
            drawChartOpenCloseTickets();
            drawChartSatisfaction();
        };
    </script>
    <div class="dashboard-data-container">
        <div class="dashboard-title">
            <h1 class="dashboard-total">{{ $users->count() }} users | {{ $tickets->count() }} tickets</h1>
        </div>
        <div class="dashboard-column">
            <div class="dashboard-card">
                <div id="donutCategories"></div>
            </div>
            <div class="dashboard-card">
                <div id="donutStatuses"></div>
            </div>
            <div class="dashboard-card">
                <div id="GraphOpenCloseTickets"></div>
            </div>
            <div class="dashboard-card">
                <div id="ChartSatisfaction"></div>
            </div>
        </div>
    </div>
@endsection
