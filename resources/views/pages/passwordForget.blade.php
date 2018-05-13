<!doctype html>
<html>
<head>
    @include('includes.header')
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>

<body>
    <section class="hero is-success is-fullheight" id="page-container">
        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="column is-4 is-offset-4">
                    <img width="100%" src="{{ asset('img/logo.svg') }}">
                    <div class="box">
                        <h1>Wachtwoord vergeten</h1>
                        <form @submit.prevent="send" @keydown="user.errors.clear($event.target.name)" >  
                            <div class="field">
                                <div class="control">
                                    <input class="input is-large" type="email" placeholder="Email" v-model='user.email' autofocus required>
                                </div>
                            </div>
                            <transition-group name="errorTag" tag="p">
                                <span class="inputError" v-bind:key="user.errors.get('email')" v-cloak>
                                    @{{ user.errors.get('email') }}
                                </span>
                            </transition-group>
                            <button class="button is-block is-info is-large is-fullwidth">Verzenden</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        let innerVue = new Vue({
            el: '#page-container',

            data: {
                user: {
                    email: 'chiel1997@hotmail.com',
                    errors: new Errors()
                }
            },

            created() {

            },

            methods: {
                send(){
                    axios.post('/api/login/forget', this.$data.user)
                    .then(function(response) {
                    })
                    .catch(error => this.user.errors.record(error.response.data.errors));
                }
            }
        });
        </script>
</body>
</html>