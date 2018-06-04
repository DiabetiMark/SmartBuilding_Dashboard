<template>
    <div>
        <select v-model='user_id'>
            <option v-for="user in users" :value="user.id" @change="userChanged">{{user.username}}</option>
        </select>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        props: ['admin'],

        data(){
            return{
                users: '',
                userInfo: '',
                user_id: '',
            }
        },

        mounted() {
        },

        created(){
            this.getData();
        },

        methods: {
            getData(){
                axios.get('/api/user')
                .then(response => {
                    this.users = response.data;
                    this.user_id = response.data[0].id;
                }).catch(e => {
                    console.log(e);
                });
            },
            userChanged(){
                console.log("pils");
                axios.get('/user/rooms/' + self.user_id)
                .then(response => {
                    self.userInfo = response.data;
                }).catch(e => {
                    console.log(e);
                });
            }
        }
    }
</script>
