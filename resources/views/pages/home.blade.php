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
        <div class="columns">
            <div class="column">
                <canvas id="reedChart"></canvas>
            </div>
            <div class="column">

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

        // Reed
        new Chart(document.getElementById("reedChart"), {
            "type": "line",
            "data": {
                "labels": ["07:00", "08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00"],
                "datasets": [{
                    "label": "Deur open ja(1) of nee(0)",
                    "steppedLine": true,
                    "data": [false, false, true, true, true, false, true, true, true, true, false],
                    "fill": false,
                    "borderColor": "rgb(50, 115, 220)"
                }]
            },
            "options": {
                layout: {
                    padding: {
                        top: 50,
                        bottom: 50
                    }
                }
            }
        });

        setInterval(function(){
            let hours = new Date().getHours();
            let minutes = new Date().getMinutes();

            chart.data.labels.push(hours + ':' + minutes);
            chart.data.datasets[0].data.push(Math.floor(Math.random() * (21 - 19     + 1)) + 18);
            chart.update();
        }, 1000 * 5);
    </script>
@stop