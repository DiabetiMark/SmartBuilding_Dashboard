<meta charset="utf-8">
<meta http-equiv="x-ua-compatible" content="ie=edge">
<title>Aareon | Smart Building</title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="manifest" href="site.webmanifest">
<link rel="apple-touch-icon" href="icon.png">
<!-- Place favicon.ico in the root directory -->

<link href="{{ asset('css/bulma.css') }}" rel="stylesheet">
<link href="{{ asset('css/custom.css') }}" rel="stylesheet">

<!-- We need Chart.js in the header so it loads before all the content -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js"></script>

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="{{ URL::asset('js/vue-cookies.js') }}"></script>
<script src="{{ URL::asset('js/errors.js') }}"></script>

<script>
    Vue.config.devtools = true;
    window.axios.defaults.headers.common = {
        'X-CSRF-TOKEN' : '{{csrf_token()}}',
        'X-Requested-With' : 'XMLHttpRequest',
        'Accept' : 'application/json',
        'Authorization' : 'Bearer ' + this.$cookies.get('bearer'),
    };
</script>