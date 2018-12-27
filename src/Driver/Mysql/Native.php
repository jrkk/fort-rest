<?php
namespace App\Driver\Mysql;

use App\Prototype\Database;
use App\Config\MySqlConfig;

class Native implements Database {

    protected $connection = null;

    function __construct()
    {
        \App\Core\System::log('notice', 'Mysql Native driver has intiated');
    }

    public function connect() {
        $this->connection = mysqli_connect(
            MySqlConfig::HOST,
            MySqlConfig::USERNAME,
            MySqlConfig::PASSWORD, 
            MySqlConfig::DATABASE
        );
    }

    public function close() {
        if($this->connection !== null) {
            mysqli_close($this->connection);
        }
    }

    public function persist()
    {
        
    }

    public function update() {

    }

    public function retrive()
    {
        
    }

    public function remove() {
        
    }
    
}