@component('mail::message')

<p>Beste {{  $name }}</p>
<p>Klik hier om voor de eerste keer in te loggen op het dashboard van Aareon</p>
<br>
<p>Uw inlog gegevens zijn:</p>
<p>gebruikersnaam: {{  $username }}</p>
<p>wachtwoord: {{  $hash }}</p>

@component('mail::button', ['url' => $url])
inloggen
@endcomponent

@endcomponent
