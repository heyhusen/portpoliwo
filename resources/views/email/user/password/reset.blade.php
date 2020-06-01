@component('mail::message')
# Reset Password

You are receiving this email because we received a password reset request for your account.

@component('mail::button', ['url' => $url . '/password/reset/' . $token])
Reset Password
@endcomponent

This password reset link will expire in {{ $count }} minutes.

If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
