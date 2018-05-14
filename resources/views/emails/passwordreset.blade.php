@component('mail::message')

<p>Beste {{  $name }}</p>
<p>Klik op de link om uw wachtwoord aan te passen</p>

@component('mail::button', ['url' => $url])
Reset wachtwoord
@endcomponent

@endcomponent
