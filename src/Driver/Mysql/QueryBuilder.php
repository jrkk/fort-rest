<?php
namespace App\ORM;

class MySqlQueryBuilder {
    
    public function select(string $select = "*", bool $mode = false) : self {
        return $this;
    }

    public function where(string $column , $value) : self {

        return $this;
    }

    public function where_in(string $column, array $data) : self {

        return $this;
    }

    public function insert() {
        
    }

}