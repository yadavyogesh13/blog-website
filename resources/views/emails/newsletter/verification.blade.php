@component('mail::message')
# Verify Your Newsletter Subscription

Hello!

Thank you for subscribing to our newsletter. To complete your subscription and start receiving updates from **{{ config('app.name') }}**, please verify your email address by clicking the button below.

@component('mail::button', ['url' => $verificationUrl])
Verify Email Address
@endcomponent

If you didn't request this subscription, you can safely ignore this email or unsubscribe using the link below.

@component('mail::subcopy')
If you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below into your web browser:

[{{ $verificationUrl }}]({{ $verificationUrl }})
@endcomponent

Thanks,<br>
The {{ config('app.name') }} Team

@component('mail::footer')
[Unsubscribe]({{ $unsubscribeUrl }}) from our newsletter
@endcomponent
@endcomponent