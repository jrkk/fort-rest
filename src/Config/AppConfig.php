<?php
namespace App\Config;

class AppConfig {
    const MySqlDriver = true;
    const APPDIR = '/web';
    const PATHDELIMITER = "\\";
    const Logger = \App\Driver\Logger\NullLogger::class;
    const PreLoads = [
        'entities' => [
            
        ],
        'services' => [

        ],
    ];
}