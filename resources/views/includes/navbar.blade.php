<nav class="navbar" id="nav">
    <div class="navbar-brand">
        <a class="navbar-item" href="http://bulma.io">
            <img src="{{ asset('img/logo.png') }}" alt="SmartBuilding Aareon" width="112" height="28">
        </a>
        <a class="navbar-item is-hidden-desktop" href="#" target="_blank">
        </a>
        <div class="navbar-burger burger" data-target="navMenubd-example">
            <span>1</span>
            <span>2</span>
            <span>3</span>
        </div>
    </div>
    <div id="navMenubd-example" class="navbar-menu">
        <div class="navbar-end">
            <a class="navbar-item" href="#">
                <i class="far fa-user"></i>&nbsp;Account
            </a>
            <a class="navbar-item" @click="logout">
                <i class="fas fa-sign-out-alt"></i>&nbsp;Uitloggen
            </a>
        </div>
    </div>
</nav>
<div class="columns is-fullheight">
    <div class="column is-2 is-sidebar-menu is-hidden-mobile">
        <div id="app">
            <nav-component></nav-component>
        </div>
    </div>
    <div class="column is-main-content">
        @yield('content')
    </div>
</div>
<script>
let navVue = new Vue({
        el: '#nav',

        data: {
            user: '',
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
            logout(){
                window.axios.defaults.headers.common = {
                    'Authorization' : 'Bearer ' + this.$cookies.get('bearer'),
                };

                axios.post('/api/logout')
                .then(function (response) {
                    this.$cookies.remove('bearer');
                    window.location.replace('/login');
                }).catch(response => console.log(response));
            }
        }
    });
</script>