@component('mail::message')
# Письмо подтверждения регистрации

Пожалуйста, пройдите по следующей ссылке для завершения регистрации:

@component('mail::button', ['url' => route('register.verify', ['token' => $user->verify_token])])
    Подтвердить E-mail
@endcomponent

Спасибо,<br>
{{ config('app.name') }}
@endcomponent
