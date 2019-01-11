<?php
namespace App\Driver\Mysql;

use App\Prototype\Database;
use App\Config\MySqlConfig;

class Native implements Database {

    protected static $connection = null;

    public static function connect() {
        if(self::$connection != null) {
            self::$connection = mysqli_connect(
                MySqlConfig::HOST,
                MySqlConfig::USERNAME,
                MySqlConfig::PASSWORD, 
                MySqlConfig::DATABASE
            );
        } 
    }

    public static function close() {
        if(self::$connection != null) {
            mysqli_close(self::$connection);
        }
    }

    public static function persist(string $query = '', string $formatString = '', array $data = [])
    {
        \var_export(\func_get_args());
    }

    public static function update(string $query = '', string $formatString = '', array $data = []) {
        \var_export(\func_get_args());
    }

    public static function retrive(string $query = '', string $formatString = '', array $data = [])
    {
        \var_export(\func_get_args());
    }

    public static function remove() {
        
    }
    
}