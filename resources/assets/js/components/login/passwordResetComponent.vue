<template>
    <form @submit.prevent="send" @keydown="user.errors.clear($event.target.name)" >
        <div class="field">
            <div class="control">
                <input class="input is-large" type="password" name="password" placeholder="Wachtwoord" v-model='user.password' autofocus required v-cloak>
            </div>
        </div>
        <div class="field">
            <div class="control">
                <input class="input is-large" type="password" name="password" placeholder="Wachtwoord bevestigen" v-model='user.password_confirmation' required v-cloak>
            </div>
        </div>
        <span class="inputError" v-bind:key="user.errors.get('password')" v-cloak>
            {{ user.errors.get('password') }}
        </span>
        <button class="button is-block is-info is-large is-fullwidth" >Opslaan</button>
    </form>
</template>
<script>
    import axios from 'axios';

    export default {
        mounted() {
            console.log('passwordresetcomponent mounted')
        },

        data(){
            return{
                user: {
                    user_id: '{{ $user_id }}',
                    email: '{{ $email }}',
                    hash: '{{ $hash }}',
                    password: '',
                    password_confirmation: '',
                    errors: new Errors()
                }
            }
        },

        methods: {
            send: function(){
                axios.post('/api/login/wachtwoord/update', this.$data.user)
                .then(window.location = '/account/login')
                .catch(error => this.user.errors.record(error.response.data.errors));
            }
        }
    }
</script>