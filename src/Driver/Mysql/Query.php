<?php
namespace App\Driver\Mysql;

class Query {

    protected $select = "";
    protected $updates = [];

    protected $setClause = [];
    protected $whereClause = [];
    protected $groupClause = [];
    protected $havingClause = [];
    protected $formatString = "";

    protected $table = "";
    protected $joins = [];

    protected $params = [];

    protected $_query = "";
    protected $_binds = "";
    protected $_params = [];

    function __construct()
    {
        \App\core\System::log('notice', 'New Query Builder class initiated');
    }
    
    public function select(array $fields = ["*"], bool $mode = false) : self {
        if(count($fields) == 1 && $fields[0] == "*")  {
            $this->select = "*";
        } else {
            foreach($fields as $index => $field) {
                if(is_numeric($index)) {
                    if($mode === false) 
                        $this->select .= "`,{$field}`";
                    else
                        $this->select .= ",{$field}";
                } else {
                        $this->select .= ",{$index}";
                }
            }
            $this->select = ltrim($this->select, ',');
        }
        return $this;
    }

    public function where(string $column , $value, string $operand = "=") : self {
        $this->whereClause[] = [
            '_column' => $column,
            '_value' => $value,
            '_operand' => $operand
        ];
        return $this;
    }

    public function where_in(string $column, array $assoc) : self {
        $this->whereClause[] = [
            '_column' => $column,
            '_value' => " (".implode(",", array_values($assoc)).") ",
            '_operand' => " IN "
        ];
        return $this;
    }

    public function where_not_in(string $column, array $assoc) : self {
        $this->whereClause[] = [
            '_column' => $column,
            '_value' => " (".implode(",", array_values($assoc)).") ",
            '_operand' => " NOT IN "
        ];
        return $this;
    }

    public function group_by(string $column) : self {
        $this->groupClause[] = $column;
        return $this;
    }

    public function having(string $column , $value, string $operand = "=") : self {
        $this->havingClause[] = [
            '_column' => $column,
            '_value' => $value,
            '_operand' => $operand
        ];
        return $this;
    }

    public function from(string $table, string $alias = "") : self {
        $this->table = $table . ( $alias === "" ?  "" : $alias);
        return $this;
    }

    public function join(string $table, string $alias = "", string $on = "", string $join_type = "INNER") {
        $this->joins[]  = [
            '_table' => $table,
            '_alias' => $alias,
            '_on' => $on,
            '_join' => $join_type
        ];
        return $this;
    }

    public function insert(string $table, array $data = []) {
        $this->_query = "INSERT INTO {$table} ";
        $columnsList = "";
        $valuesList = "";
        foreach($data as $column => $value) {
            $columnsList .= ",`{$column}`";
            $valuesList .= ",?";
            $this->formatString($value);
            $this->params($value);
        }
        $this->_query .= " ({$columnsList}) VALUES ({$valuesList}) ";
        return $this;
    }

    public function update(string $table, $) {

    }

    public function getQuery() : string {
        return $this->_query;
    }

    public function getBindParamsFormat() : string {
        return $this->_binds;
    }

    public function getBindPrams() : array {
        return $this->_params;
    }

    protected function getParamFormat($value) {
        if( is_int($value) || is_bool($value) ) {
            $this->_binds .= "i";
        } else if ( is_float($value) ) {
            $this->_binds .= "d";
        } else if ( is_string($value) && strlen($value) < 4096 ) {
            $this->_binds .= "s";
        } else {
            $this->_binds .= "b";
        }
    }

    protected function clean() {

    }

}