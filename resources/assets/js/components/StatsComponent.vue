<template>
    <nav class="level">
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Ruimtes</p>
                <p class="title">{{ rooms }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Modules</p>
                <p class="title">{{ modules }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Sensoren</p>
                <p class="title">{{ sensors }}</p>
            </div>
        </div>
        <div class="level-item has-text-centered">
            <div>
                <p class="heading">Status</p>
                <p class="title"><span class='tag' v-bind:class="{'is-success': state[0] == 'is-success', 'is-danger': state[0] == 'is-danger'}">{{ state[1] }}</span></p>
            </div>
        </div>
    </nav>
</template>

<script>
    import axios from 'axios';

    export default {
        mounted() {
            console.log('StatsComponent mounted')
        },

        data(){
            return{
                rooms: 0,
                modules: 0,
                sensors: 0,
                state: []
            }
        },

        methods: {
            getTotals: function(){
                axios.get('/api/room').then(response => {
                    this.rooms = response.data.length;
                }).catch(e => {
                    console.log(e);
                });
                axios.get('/api/sensormodule').then(response => {
                    this.modules = response.data.length;
                }).catch(e => {
                    console.log(e);
                });
                axios.get('/api/sensor').then(response => {
                    this.sensors = response.data.length;
                }).catch(e => {
                    console.log(e);
                });
            },

            getState: function(){
                this.state = ['is-success', 'Online'];
                // this.state = ['is-danger', 'Offline'];
            }
        },

        created(){
            this.getTotals();
            this.getState();
        }
    }
</script>
