@component('mail::message')
# Contact Us

<p>Name: <b>{{ $data['name'] }}</b></p>
<p>Phone: <b>{{ $data['phone'] }}</b></p>
<p>Email: <b>{{ $data['email'] }}</b></p>
<p>Message: <b>{{ $data['message'] }}</b></p>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
