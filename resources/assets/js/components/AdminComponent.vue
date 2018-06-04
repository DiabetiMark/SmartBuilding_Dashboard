<template>
    <div>

    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: ['admin'],

        data(){
            return{
                users: '',
            }
        },

        mounted() {
        },

        created(){
            this.getData();
        },

        methods: {
            getData(){
                axios.get('/api/room/' + this.roomId + '/getAllValues').then(response => {

                }).catch(e => {
                    console.log(e);
                });
            }
        }
    }
</script>
