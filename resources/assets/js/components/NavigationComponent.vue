<template>
    <aside class="menu">
        <p class="menu-label"><i class="fas fa-home"></i></i>&nbsp;Algemeen</p>
        <ul class="menu-list">
            <li><a href="{{ url('/') }}" {{ (Request::is('/') ? 'class=is-active' : '') }}>Overzicht</a></li>
        </ul>
        <p class="menu-label"><i class="fas fa-server"></i>&nbsp;Ruimtes</p>
        <ul class="menu-list">
            <li><a href="{{ url('/overview') }}" {{ ((Request::is('overview') || Request::is('overview/*')) ? 'class=is-active' : '') }}>Ruimteoverzicht</a></li>
        </ul>
        <template v-if="user.role_id == 3" v-cloak>
            <p class="menu-label"><i class="fas fa-cogs"></i>&nbsp;Beheer</p>
            <ul class="menu-list">
                <li><a href="{{ url('/settings') }}" {{ (Request::is('settings') ? 'class=is-active' : '') }}>Instellingen</a></li>
                <li><a href="{{ url('/users') }}" {{ (Request::is('users') ? 'class=is-active' : '') }}>Gebruikers</a></li>
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
            }
        },

        mounted() {
            console.log('NavComponent mounted');
        },

        created(){
            this.getAuthUser();
        },

        methods: {
            getAuthUser(){
                axios.get('/api/getAuthUser')
                .then(function (response) {
                    navVue.user = response.data;
                }).catch(response => console.log(response));
            },
        }
    }
</script>
