<template>
    <div class="box">
        <div class="notification is-danger" v-if="!user.errors.empty()">
            <span class="inputError" v-bind:key="user.errors.get('password')" v-cloak>
            {{ user.errors.get('password') }}
            </span>
        </div>
        <form @submit.prevent="login" @keydown="user.errors.clear($event.target.name)">
            <div class="field">
                <div class="control">
                    <input class="input is-large" name="username" type="text" placeholder="Gebruikersnaam" v-model='user.username' autofocus required>
                </div>
            </div>
            <div class="field">
                <div class="control">
                    <input class="input is-large" name="password" type="password" placeholder="Wachtwoord" v-model='user.password' required>
                </div>
            </div>
            <div class="field">
                <label class="checkbox">
                    <input type="checkbox">
                    Onthoud mij
                </label>
            </div>

            <!-- Als we het doen moeten we het goed doen met animaties 😎 -->
            <button class="button is-block is-info is-large is-fullwidth" v-if="loadingSpinner">
                <svg class="svg-inline--fa fa-spinner fa-w-16 fa-spin" style="font-size: 24px;" aria-hidden="true" data-prefix="fa" data-icon="spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z"></path></svg>
            </button>
            <button class="button is-block is-info is-large is-fullwidth" v-else>Login</button>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        mounted() {
            console.log('LoginComponent mounted');
        },

        data(){
            return{
                user: {
                    username: 'admin',
                    password: 'admin123',
                    errors: new Errors()
                },
                loadingSpinner: false
            }
        },

        methods: {
            login: function(){
                let self = this;
                this.loadingSpinner = true;
                axios.post('/api/login', this.$data.user)
                .then(function(response){
                    $cookies.set('bearer', response.data.success.token, 86400, '/');
                    window.location = '/';
                })
                .catch(function(error){
                    self.user.errors.record(error.response.data.errors);
                    self.loadingSpinner = false;
                });
            }
        }
    }
</script>