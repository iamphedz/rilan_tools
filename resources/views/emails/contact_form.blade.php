@component('mail::layout')
@slot('header')
@component('mail::header',[ 'url' => url('/') ])
Contact Message
@endcomponent
@endslot
From: <br>{{ $contact_message['name'] }} / {{ $contact_message['email'] }}

Message:<br>
{{ $contact_message['message'] }}

@slot('footer')
@component('mail::footer')
Rilan Tool Supply
<p>#32 Sauyo Road, Sauyo, Novaliches, Quezon City
    <br />Contact No.: 0938-735-7344 / 0975-434-9910</p>
@endcomponent
@endslot
@endcomponent