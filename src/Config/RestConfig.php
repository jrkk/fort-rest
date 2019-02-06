<?php
namespace App\Config;

class RestConfig {
    const XssClean = false;
    const CsrfTokens = false;
    const Oauth = true;
    const Authorization = 'Basic';
    const Driver = \App\Driver\Oauth\BasicAuthorization::class;
    const LADP = false;
    const AcceptMimes = [
        'application/json' => 'json',
        'application/x-www-form-urlencoded' => 'html',
    ];
    const ResponseMimes = [
        'application/json' => 'json'
    ];
    const AllowedOrigins = [
        '*',
        '127.0.0.1' // testing 
    ];
}

