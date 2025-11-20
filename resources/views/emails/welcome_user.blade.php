@component('mail::message')
# Bonjour {{ $user->name }},

Merci de vous être inscrit sur notre site 😄

Votre email : **{{ $user->email }}**

@component('mail::button', ['url' => url('/')])
Aller sur le site
@endcomponent

À bientôt,  
L’équipe {{ config('app.name') }}
@endcomponent
