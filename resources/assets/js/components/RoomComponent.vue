<template>
    <div>
        <ul v-if="modules && modules.length">
            <h1>Room <b>1</b> has the following ({{ modules.length }}) sensors:</h1>
            <li v-for="module of modules">
                <b>ID</b>: {{ module.pivot.sensor_module_id }}
            </li>
        </ul>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        mounted() {
            console.log('RoomComponent mounted')
        },

        data(){
            return{
                modules: [],
                allTemperatures: [],
                errors: []
            }
        },

        methods: {
            loadModulesForRoom: function(roomId){
                axios.get('/api/room/sensormodules/' + roomId).then(response => {
                    this.modules = response.data;
                }).catch(e => {
                    this.errors.push(e);
                });
            },

            getTemperatures: function(roomId){
                axios.get('/api/sensormodule/dataregister/' + roomId).then(response => {
                    this.allTemperatures = response.data;
                }).catch(e => {
                    this.errors.push(e);
                });
            }
        },

        created(){
            this.loadModulesForRoom(1);
            this.getTemperatures(1);
        }
    }
</script>
