@component('mail::message')
# Email Confirmation

Пожалуйста, пройдите по следующей ссылке для завершения регистрации:

@component('mail::button', ['url' => route('register.verify', ['token' => $user->verify_token])])
    Verify Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
