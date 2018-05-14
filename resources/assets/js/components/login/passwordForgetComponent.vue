
<template>
    <form @submit.prevent="send" @keydown="user.errors.clear($event.target.name)" >  
        <div class="field">
            <div class="control">
                <input class="input is-large" type="email" name="email" placeholder="Email" v-model='user.email' autofocus required>
            </div>
        </div>
        <span class="inputError" v-bind:key="user.errors.get('email')" v-cloak>
            {{ user.errors.get('email') }}
        </span>
        <button class="button is-block is-info is-large is-fullwidth">Verzenden</button>
    </form>
</template>
<script>
    import axios from 'axios';

    export default {
        mounted() {
            console.log('password-forget-component mounted')
        },

        data(){
            return{
                user: {
                    email: 'chiel1997@hotmail.com',
                    errors: new Errors()
                }
            }
        },

        methods: {
            send: function(){
                axios.post('/api/login/forget', this.$data.user)
                .catch(error => this.user.errors.record(error.response.data.errors));
            }
        }
    }
</script>