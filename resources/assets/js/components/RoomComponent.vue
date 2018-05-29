<template>
    <div >
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
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: ['room'],

        data(){
            return{
                roomId: roomId,
                modules: [],
                data: {
                    'temperatuur': [],
                    'luchtvochtigheid': []
                },
            }
        },

        mounted() {
            console.log('RoomComponent mounted');
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
                        });
                    });

                    this.data = data;
                    this.modules = modules;
                    this.populateGraphs();
                    console.log(data);
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
