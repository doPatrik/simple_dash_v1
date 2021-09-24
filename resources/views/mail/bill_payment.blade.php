@component('mail::message')
# Értesítés befizetetlen számlákról!
@component('mail::panel')
<table border="1">
<thead>
<tr>
<th>Számla</th>
<th>Összeg</th>
<th>Határidő</th>
</tr>
</thead>
<tbody>
<tr>
<td>{{$provider->label}} számla</td>
<td>{{$bill->price}} Ft</td>
<td>{{$bill->remaining_day}}</td>
</tr>
</tbody>
</table>
@endcomponent
@component('mail::button', ['url' => route('payment.index')])
    Számlák
@endcomponent

Köszönjük,<br>
{{ config('app.name') }}
@endcomponent
