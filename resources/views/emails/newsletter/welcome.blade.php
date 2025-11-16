@component('mail::message')
# Welcome to Our Newsletter! 🎉

Hello!

Thank you for verifying your email address. You are now officially subscribed to the **{{ config('app.name') }}** newsletter!

## What to Expect:
- 📚 Latest articles and blog posts
- 💡 Helpful tips and tutorials
- 🚀 New features and updates
- 📅 Weekly roundups of popular content

We're excited to have you as part of our community and look forward to sharing valuable content with you.

@component('mail::button', ['url' => url('/')])
Visit Our Website
@endcomponent

If you ever wish to unsubscribe, you can do so at any time using the link below.

Thanks,<br>
The {{ config('app.name') }} Team

@component('mail::footer')
[Unsubscribe]({{ $unsubscribeUrl }}) from our newsletter
@endcomponent
@endcomponent