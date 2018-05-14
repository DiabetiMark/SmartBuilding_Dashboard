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
                        <div id="app"><login-component></login-component></div>
                    </div>
                    <p class="has-text-grey">
                        <a href="{{ url('/login/forget') }}">Wachtwoord vergeten?</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
    @include('includes.footer')
</body>
</html>