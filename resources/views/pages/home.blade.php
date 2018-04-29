@extends('layouts.default')

@section('content')
    <!-- Large title bar -->
    <div class="tile is-ancestor">
        <div class="tile is-horizontal">
            <div class="tile is-parent">
                <article class="tile is-child notification notification-box is-aareon">
                    <nav class="level">
                        <div class="level-item has-text-centered">
                            <div>
                                <p class="heading">Ruimtes</p>
                                <p class="title">3</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                                <p class="heading">Sensoren</p>
                                <p class="title">16</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                                <p class="heading">Metingen</p>
                                <p class="title">301</p>
                            </div>
                        </div>
                        <div class="level-item has-text-centered">
                            <div>
                                <p class="heading">Status</p>
                                <p class="title"><span class="tag is-success">Online</span></p>
                            </div>
                        </div>
                    </nav>
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

    <!-- Provide our graphs with the data it needs -->
    <script>
        // Temperature
        new Chart(document.getElementById("tempChart"), {
            "type": "line",
            "data": {
                "labels": ["Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag", "Zondag"],
                "datasets": [{
                    "label": "Gemiddelde temperatuur in alle ruimtes in ℃",
                    "data": [20.6, 21.3, 21.1, 20.8, 22.1, 21, 20.6],
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
                "labels": ["Maandag", "Dinsdag", "Woensdag", "Donderdag", "Vrijdag", "Zaterdag", "Zondag"],
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
    </script>
@stop