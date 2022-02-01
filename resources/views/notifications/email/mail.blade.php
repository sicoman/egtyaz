@component('mail::message')

@includeIf( $mail_template , $mail_data )

Regards , <br>

{{ config('app.name') }}

@endcomponent