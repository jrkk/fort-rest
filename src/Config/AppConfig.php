<?php
namespace App\Config;

class AppConfig {
    const MySqlDriver = true;
    const APPDIR = '/web';
    const PATHDELIMITER = "\\";
    const Logger = \App\Driver\Logger\NullLogger::class;
    const PreLoads = [
        ['token',\App\Entity\Token::class],
        ['user',\App\Entity\User::class]
    ];
}