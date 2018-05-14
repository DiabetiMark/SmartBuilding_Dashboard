<template>
    <form @submit.prevent="login" @keydown="user.errors.clear($event.target.name)">
        <div class="field">
            <div class="control">
                <input class="input is-large" name="username" type="text" placeholder="Gebruikersnaam" v-model='user.username' autofocus required>
            </div>
        </div>
        <span class="inputError" v-bind:key="user.errors.get('username')" v-cloak>
            {{ user.errors.get('username') }}
        </span>
        <div class="field">
            <div class="control">
                <input class="input is-large" name="password" type="password" placeholder="Wachtwoord" v-model='user.password' required>
            </div>
        </div>
        <span class="inputError" v-bind:key="user.errors.get('password')" v-cloak>
            {{ user.errors.get('password') }}
        </span>
        <div class="field">
            <label class="checkbox">
                <input type="checkbox">
                Onthoud mij
            </label>
        </div>
        <button class="button is-block is-info is-large is-fullwidth">Login</button>
    </form>
</template>
<script src="{{ URL::asset('js/vue-cookies.js') }}"></script>
<script>
    import axios from 'axios';

    export default {
        mounted() {
            console.log('LoginComponent mounted')
        },

        data(){
            return{
                user: {
                    username: 'Chiel timmermans',
                    password: '12345678',
                    errors: new Errors()
                }
            }
        },

        methods: {
            login: function(){
                axios.post('/api/login', this.$data.user)
                .then(function(response){
                    $cookies.set('bearer', response.data.success.token, 86400, '/'); 
                    window.location = '/';
                })
                .catch(function(error){
                    this.user.errors.record(error.response.data.errors)
                });
            }
        }
    }
</script>