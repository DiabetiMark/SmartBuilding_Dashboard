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
                        <div class="field">
                            <div class="control">
                                <input class="input is-large" type="password" placeholder="Wachtwoord" v-model='user.password' autofocus required>
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <input class="input is-large" type="password" placeholder="Wachtwoord bevestigen" v-model='user.password_confirmation' required>
                            </div>
                        </div>
                        <button class="button is-block is-info is-large is-fullwidth" @click="send">Opslaan</button>
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
                    user_id: '{{ $user_id }}',
                    email: '{{ $email }}',
                    hash: '{{ $hash }}',
                    password: '',
                    password_confirmation: '',
                    errors: new Errors()
                }
            },

            created() {

            },

            methods: {
                send(){
                    axios.post('/api/login/wachtwoord/update', this.$data.user)
                    .then(function(response) {
                        this.$cookies.set('bearer', response.data.success.token, 86400, '/');
                        window.location.replace('/');
                    })
                    .catch(error => this.user.errors.record(error.response.data.errors));
                }
            }
        });
        </script>
</body>
</html>