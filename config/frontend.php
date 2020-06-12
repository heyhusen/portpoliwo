<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Frontend URL
    |--------------------------------------------------------------------------
    |
    | This value is the url of your client application. 
    |
    */

    'url' => env('FRONTEND_URL', 'http://127.0.0.1:80'),

     /*
    |--------------------------------------------------------------------------
    | Frontend password reset URL
    |--------------------------------------------------------------------------
    |
    | This value is the path of your client application's password reset. 
    |
    */

    'password_reset_url' => env('FRONTEND_PASSWORD_RESET_URL', '/password/reset/'),

     /*
    |--------------------------------------------------------------------------
    | Frontend email verification URL
    |--------------------------------------------------------------------------
    |
    | This value is the path of your client application's email verification. 
    |
    */

    'email_verify_url' => env('FRONTEND_EMAIL_VERIFY_URL', '/email/verify?queryURL='),

];
