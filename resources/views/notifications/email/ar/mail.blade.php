@component('mail::message')

@includeIf( $mail_template , $mail_data )

تحياتنا , <br>

{{ config('app.name') }}

@endcomponent