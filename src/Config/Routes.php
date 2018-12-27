<?php

$routeConfig = new stdClass();

$routeConfig->routes = [
    'default' => [
        'controller' => 'HomeController',
        'method' => 'start',
        'allowedMethods' => ['GET','POST'],
        'allowedOrigins' => [ "*" ],
        'allowedSchemes' => ['http','https'],
        'regx' => '',
        'matches' => []
    ],
    'page12' => [
        'controller' => 'HomeController',
        'method' => 'start',
        'allowedMethods' => ['GET','POST'],
        'allowedOrigins' => [ "*" ],
        'allowedSchemes' => ['http','https'],
        'regx' => '(/page/)(:num)',
        'matches' => []
    ]
];

$routeConfig->acceptMime = 'applicaiton/json';

return $routeConfig;
