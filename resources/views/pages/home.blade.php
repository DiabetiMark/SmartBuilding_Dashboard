@extends('layouts.default')

@section('content')
    <!-- Large title bar -->
    <div class="tile is-ancestor">
        <div class="tile is-horizontal">
            <div class="tile is-parent">
                <article class="tile is-child notification notification-box is-aareon">
                    <div id="app"><stats-component></stats-component></div> <!-- VUE -->
                </article>
            </div>
        </div>
    </div>
    <!-- Large title bar END -->

    <!-- Graphs -->
    <section class="section">
        <div class="columns">
            <div class="column">
                <canvas id="tempChart"></canvas>
            </div>
            <div class="column">
                <canvas id="humidityChart"></canvas>
            </div>
        </div>
    </section>
    <!-- Graphs END -->

    <!-- TODO VUE -->
    <!--<div id="app"><room-component></room-component></div>-->

    <!-- Provide our graphs with the data it needs -->
    <script>
        // Temperature
        var chart = new Chart(document.getElementById("tempChart"), {
            "type": "line",
            "data": {
                "labels": [],
                "datasets": [{
                    "label": "Gemiddelde temperatuur in alle ruimtes in ℃",
                    "data": [],
                    "fill": false,
                    "borderColor": "rgb(54, 162, 235)",
                    "lineTension": .3
                }]
            },
            "options": {}
        });

        // Humidity
        new Chart(document.getElementById("humidityChart"), {
            "type": "bar",
            "data": {
                "labels": ["Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag"],
                "datasets": [{
                    "label": "Gemiddelde luchtvochtigheid in alle ruimtes in percentages",
                    "data": [52, 53, 52, 55, 57, 57, 51],
                    "fill": false,
                    "backgroundColor": ["rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)", "rgba(54, 162, 235, 0.2)"],
                    "borderColor": ["rgb(54, 162, 235)", "rgb(54, 162, 235)", "rgb(54, 162, 235)", "rgb(54, 162, 235)", "rgb(54, 162, 235)", "rgb(54, 162, 235)", "rgb(54, 162, 235)"],
                    "borderWidth": 1
                }]
            },
            "options": {
                "scales": {
                    "yAxes": [{
                        "ticks": {
                            "beginAtZero": true
                        }
                    }]
                }
            }
        });

        setInterval(function(){
            let hours = new Date().getHours();
            let minutes = new Date().getMinutes();

            chart.data.labels.push(hours + ':' + minutes);
            chart.data.datasets[0].data.push(Math.floor((Math.random() * 3)));
            chart.update();
        }, 1000 * 5);
    </script>
@stop