<?php
namespace App\Driver\Mysql;

use App\Prototype\Database;
use App\Prototype\QueryBuilder;
use App\Config\MySqlConfig;

class Native implements Database {

    protected static $connection = null;
    protected static $errors = [];

    public static function connect() {
        if(self::$connection === null) {
            self::$connection = \mysqli_connect(
                MySqlConfig::HOST,
                MySqlConfig::USERNAME,
                MySqlConfig::PASSWORD, 
                MySqlConfig::DATABASE
            );
        }
        if(self::$connection === null) {
            throw new \Exception('Mysql Driver is not connecting by given configuration');
        } 
    }

    public static function close() {
        if(self::$connection != null) {
            \mysqli_close(self::$connection);
        }
    }

    public static function persist(QueryBuilder $query)
    {
        if(self::$connection === null) throw new \Exception("Connection not established");
        $stmt = self::$connection->prepare($query->getQuery());
        if($stmt === false ) throw new \Exception("Mysql Statement not prepared");
        $result = $stmt->bind_param($query->getBindParamsFormat(), ...$query->getBindPrams());
        if($result === false) throw new \Exception('Statement not bind exception');
        if($stmt->execute() === false) {
            self::$errors[] = $stmt->error;
            return 0;
        }
        return $stmt->insert_id;
    }

    public static function update(QueryBuilder $query) 
    {
        \var_export($query);
    }

    public static function retrive(QueryBuilder $query)
    {
        \var_export($query);
    }

    public static function remove(QueryBuilder $query) {
        \var_export($query);
    }
    
}