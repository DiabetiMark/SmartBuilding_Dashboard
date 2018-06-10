<template>
    <aside class="menu" v-on:getUser="user = navVue.user">
        <p class="menu-label"><i class="fas fa-home"></i>&nbsp;Algemeen</p>
        <ul class="menu-list">
            <li><a href="/" :class="uri == '' ? 'is-active' : ''" >Overzicht</a></li>
        </ul>
        <p class="menu-label"><i class="fas fa-server"></i>&nbsp;Ruimtes</p>
        <ul class="menu-list">
            <li><a href="/overview" :class="uri == 'overview' ? 'is-active' : ''">Ruimteoverzicht</a></li>
        </ul>
        <template v-if="user.role_id == 3" v-cloak>
            <p class="menu-label"><i class="fas fa-cogs"></i>&nbsp;Beheer</p>
            <ul class="menu-list">
                <li><a href="/settings" :class="uri == 'settings' ? 'is-active' : ''">Instellingen</a></li>
                <li><a href="/users" :class="uri == 'users' ? 'is-active' : ''">Gebruikers</a></li>
            </ul>
        </template>
    </aside>
</template>
<script>
    import axios from 'axios';

    export default {
        props: ['nav'],

        data(){
            return{
                user: '',
                uri: '',
            }
        },

        mounted() {
            console.log('NavComponent mounted');
            //this.setScript();
        },

        created(){
            this.setURI();
            this.getUser();
        },

        methods: {
            getUser(){
                
            },
            setScript(){
                let cookies = document.createElement('script')
                cookies.async = true;
                cookies.src('./js/vue-cookies.js')
                document.head.appendChild(cookies)
                this.getAuthUser();
            },
            getAuthUser(){
                
                window.axios.defaults.headers.common = {
                    'Authorization' : 'Bearer ' + this.$cookies.get('bearer'),
                };

                axios.get('/api/getAuthUser')
                .then(function (response) {
                                     
                    this.user = response.data;
                }).catch(response => console.log(response));
            },
            setURI(){
                this.uri = location.pathname.substr(1)
            }
        }
    }
</script>
