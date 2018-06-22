<template>
    <div>
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="/">Home</a></li>
                <li ><a href="/overview">Overzicht</a></li>
                <li class="is-active"><a href="#" aria-current="page">{{ roomName }}</a></li>
            </ul>
        </nav>
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <div class="title">{{ roomName }}</div>
                </div>
            </div>
            <div class="level-right">
                <div class="level-item">
                    <div id="datedropdown" class="dropdown is-right">
                        <div class="control">
                            <div class="select">
                                <select v-model="selectedDate">
                                    <option v-for="(date, index) in dates" href="#" class="dropdown-item" :class="{ 'is-active': index === 0 }">{{ date }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div>{{ roomDescription }}</div>
        <hr>

        <div v-if="data.temperatuur.length === 0 && data.luchtvochtigheid.length === 0" class="notification is-warning has-text-centered">
            <strong>Er kon geen data worden gevonden voor deze ruimte</strong>
        </div>

        <div class="columns">
            <div class="column">
                <canvas ref="tempChart" id="tempChart"></canvas>
            </div>
            <div class="column">
                <canvas ref="humidityChart" id="humidityChart"></canvas>
            </div>
        </div>
        <hr>
        <div class="columns">
            <div class="column">
                <canvas ref="movementChart" id="movementChart"></canvas>
            </div>
            <div class="column">
                <canvas ref="reedChart" id="reedChart"></canvas>
            </div>
        </div>

        <br>
        <!--<pre>{{ data }}</pre>-->
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: ['room'],

        data(){
            return{
                loader: undefined,
                roomId: roomId,
                roomName: '',
                roomDescription: '',
                modules: [],
                data: {
                    'deur': [],
                    'methaan': [],
                    'temperatuur': [],
                    'luchtvochtigheid': [],
                    'beweging': []
                },
                selectedDate: undefined,
                dates: [],
                dateDropdown: undefined,
                charts: []
            }
        },

        mounted() {
            console.log('RoomComponent mounted');

            let dropdown = document.querySelector('.dropdown');
            dropdown.addEventListener('click', function(event) {
                event.stopPropagation();
                dropdown.classList.toggle('is-active');
            });
            window.addEventListener('click', function(){
                if(dropdown.classList.contains('is-active'))
                    dropdown.classList.toggle('is-active');
            });
            this.dateDropdown = dropdown;

            let today = new Date();
            let day = today.getDate();
            let month = today.getMonth() + 1;
            let year = today.getFullYear();
            if(day < 10) { day = '0' + day; }
            if(month < 10) { month = '0' + month; }
            this.selectedDate = year + '-' + month + '-' + day;
        },

        created(){
            this.getData();

        },

        watch: {
            selectedDate: function(){
                this.populateGraphs();
            }
        },

        methods: {
            getData(){
                axios.get('/api/room/' + this.roomId + '/getAll').then(response => {

                    let data = {
                        'deur': [],
                        'methaan': [],
                        'temperatuur': [],
                        'luchtvochtigheid': [],
                        'beweging': []
                    };

                    let modules = response.data.sensor_modules;
                    modules.forEach((module) =>{
                        module.sensors.forEach((sensor) =>{
                            //console.log(sensor);
                            sensor.data_registers.forEach((dataRegister) =>{
                                //console.log(dataRegister.field.name);
                                data[dataRegister.field.name].push({
                                   'timestamp': dataRegister.updated_at,
                                   'date': dataRegister.updated_at.split(' ')[0],
                                   'time': dataRegister.updated_at.split(' ')[1],
                                   'value': parseInt(dataRegister.value)
                                });
                                this.dates.push(dataRegister.updated_at.split(' ')[0]);
                            });
                        });
                    });

                    this.data = data;
                    this.dates = this.dates.filter(function(item, pos, self){ return self.indexOf(item) === pos});
                    this.dates = this.dates.sort(function(a, b){ return new Date(b) - new Date(a)});
                    this.modules = modules;
                    this.roomName = response.data.roomName;
                    this.roomDescription = response.data.roomDescription;
                    this.populateGraphs();
                }).catch(e => {
                    console.log(e);
                });
            },

            populateGraphs(){
                // Destroy the charts to remove objects that are still alive and thus interactable
                this.charts.forEach((chart) =>{
                    chart.destroy();
                });

                let tempTime = [];
                let tempValues = [];
                this.data.temperatuur.forEach((temp) =>{
                    if(temp.date === this.selectedDate){
                        tempTime.push(temp.time);
                        tempValues.push(temp.value);
                    }
                });

                this.charts.push(new Chart(this.$refs.tempChart, {
                    "type": "line",
                    "data": {
                        "labels": tempTime,
                        "datasets": [{
                            "label": "Gemiddelde temperatuur in alle ruimtes in ℃",
                            "data": tempValues,
                            "fill": false,
                            "borderColor": "rgb(54, 162, 235)",
                            "lineTension": .3
                        }]
                    },
                    "options": {}
                }));

                let humTime = [];
                let humValues = [];
                let humBackgrounds = [];
                let humBorders = [];
                this.data.luchtvochtigheid.forEach((hum) =>{
                    if(hum.date === this.selectedDate){
                        humTime.push(hum.time);
                        humValues.push(hum.value);
                        humBackgrounds.push("rgba(54, 162, 235, 0.2)");
                        humBorders.push("rgb(54, 162, 235)");
                    }
                });

                this.charts.push(new Chart(this.$refs.humidityChart, {
                    "type": "bar",
                    "data": {
                        "labels": humTime,
                        "datasets": [{
                            "label": "Gemiddelde luchtvochtigheid in alle ruimtes in percentages",
                            "data": humValues,
                            "fill": false,
                            "backgroundColor": humBackgrounds,
                            "borderColor": humBorders,
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
                }));

                let movementTime = [];
                let movementValues = [];
                this.data.beweging.forEach((movement) =>{
                    if(movement.date === this.selectedDate){
                        movementTime.push(movement.time);
                        movementValues.push(movement.value);
                    }
                });

                this.charts.push(new Chart(this.$refs.movementChart, {
                    "type": "line",
                    "data": {
                        "labels": movementTime,
                        "datasets": [{
                            "label": "Beweging ja(1) of nee(0)",
                            "steppedLine": true,
                            "data": movementValues,
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
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    fixedStepSize: 1
                                }
                            }],
                        }
                    }
                }));

                let reedTime = [];
                let reedValues = [];
                this.data.deur.forEach((door) =>{
                    if(door.date === this.selectedDate){
                        reedTime.push(door.time);
                        reedValues.push(door.value);
                    }
                });
                // Reed
                this.charts.push(new Chart(this.$refs.reedChart, {
                    "type": "line",
                    "data": {
                        "labels": reedTime,
                        "datasets": [{
                            "label": "Deur open ja(1) of nee(0)",
                            "steppedLine": true,
                            "data": reedValues,
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
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    fixedStepSize: 1
                                }
                            }],
                        }
                    }
                }));
            }
        }
    }
</script>
