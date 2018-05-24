<template>
    <div>
        {{ data }}
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
                data: [],
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
                    console.log(modules);
                    modules.forEach((module) =>{
                        module.data_registers.forEach((dataregister) =>{
                           data[dataregister.field.fieldName].push({
                               'timestamp': dataregister.updated_at,
                               'date': dataregister.updated_at.split(' ')[0],
                               'time': dataregister.updated_at.split(' ')[1],
                               'value': dataregister.value
                           });
                        });
                    });

                    this.data = data;
                    this.modules = modules;
                    console.log(data);
                }).catch(e => {
                    console.log(e);
                });
            }
        }
    }
</script>
