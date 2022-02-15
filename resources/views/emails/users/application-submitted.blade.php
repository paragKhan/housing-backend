@component('mail::message')
<div style="text-align: center">
    <img width="150px" height="auto"
         src="https://user-images.githubusercontent.com/60192948/153716283-edd5df1b-ce00-461d-8554-c486a41768a7.png"/>
</div>
<br>

<h1 style="font-weight: bold; text-align: center">
    We have received your application and we will inform once we begin to process your application.
</h1>

<p style="text-align: center">
    Click on the button bellow to view your application status.
</p>
@component('mail::button', ['url' => 'https://mothbahamas.com/application-status'])
    View Application Status
@endcomponent
Thanks,<br>
{{ config('app.name') }} <br>
Charlotte House, Charlotte Street,<br>
P.O.Box N275 Nassau, N.P.,The Bahamas<br>
<a href="https://www.mothbahamas.com">Visit Website</a>
@endcomponent

