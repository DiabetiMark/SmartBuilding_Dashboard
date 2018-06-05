<template>
    <div>
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/overview">Overzicht</a></li>
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
                        <div class="dropdown-trigger">
                            <button class="button is-small" aria-haspopup="true" aria-controls="dropdown-menu">
                                <span>{{ dates[0] }}</span>
                                <span class="icon is-small">
                                    <i class="fa fa-angle-down" aria-hidden="true"></i>
                                </span>
                            </button>
                        </div>
                        <div class="dropdown-menu" id="dropdown-menu" role="menu">
                            <div class="dropdown-content">
                                <a v-for="date in dates" href="#" class="dropdown-item is-active">
                                    {{ date }}
                                </a>
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

        <br>
        <pre>{{ data }}</pre>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: ['room'],

        data(){
            return{
                roomId: roomId,
                roomName: '',
                roomDescription: '',
                modules: [],
                data: {
                    'temperatuur': [],
                    'luchtvochtigheid': []
                },
                dates: [],
                dateDropdown: undefined,
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
        },

        created(){
            this.getData();
        },

        methods: {
            getData(){
                axios.get('/api/room/' + this.roomId + '/getAllValues').then(response => {
                    let data = {
                        'temperatuur': [],
                        'luchtvochtigheid': []
                    };

                    let modules = response.data.sensor_modules;
                    modules.forEach((module) =>{
                        module.data_registers.forEach((dataregister) =>{
                           data[dataregister.field.name].push({
                               'timestamp': dataregister.updated_at,
                               'date': dataregister.updated_at.split(' ')[0],
                               'time': dataregister.updated_at.split(' ')[1],
                               'value': dataregister.value
                           });

                           this.dates.push(dataregister.updated_at.split(' ')[0]);
                        });
                    });

                    this.data = data;
                    this.dates = this.dates.filter(function(item, pos, self){ return self.indexOf(item) === pos});
                    this.modules = modules;
                    this.roomName = response.data.roomName;
                    this.roomDescription = response.data.roomDescription;
                    this.populateGraphs();
                    console.log(response.data);
                }).catch(e => {
                    console.log(e);
                });
            },

            populateGraphs(){
                let tempTime = [];
                let tempValues = [];
                this.data.temperatuur.forEach((temp) =>{
                    tempTime.push(temp.time);
                    tempValues.push(temp.value);
                });

                new Chart(this.$refs.tempChart, {
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
                });

                let humTime = [];
                let humValues = [];
                this.data.luchtvochtigheid.forEach((hum) =>{
                    humTime.push(hum.time);
                    humValues.push(hum.value);
                });

                new Chart(this.$refs.humidityChart, {
                    "type": "bar",
                    "data": {
                        "labels": humTime,
                        "datasets": [{
                            "label": "Gemiddelde luchtvochtigheid in alle ruimtes in percentages",
                            "data": humValues,
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
            }
        }
    }
</script>
