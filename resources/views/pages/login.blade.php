<!doctype html>
<html>
<head>
    @include('includes.header')
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700" rel="stylesheet">
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">
</head>

<body>
    <section class="hero is-success is-fullheight">
        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="column is-4 is-offset-4">
                    <img width="100%" src="{{ asset('img/logo.svg') }}">
                    <div class="box">
                        <form action="{{ url('/') }}">
                            <div class="field">
                                <div class="control">
                                    <input class="input is-large" type="email" placeholder="Email" autofocus required>
                                </div>
                            </div>
                            <div class="field">
                                <div class="control">
                                    <input class="input is-large" type="password" placeholder="Wachtwoord" required>
                                </div>
                            </div>
                            <div class="field">
                                <label class="checkbox">
                                    <input type="checkbox">
                                    Onthoud mij
                                </label>
                            </div>
                            <button class="button is-block is-info is-large is-fullwidth">Login</button>
                        </form>
                    </div>
                    <p class="has-text-grey">
                        <a href="{{ url('/login/forget') }}">Wachtwoord vergeten?</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
</body>
</html>