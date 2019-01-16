<?php
namespace App\Driver\Mysql;

use App\Prototype\QueryBuilder;

class Query implements QueryBuilder {

    protected $_MODE = 0;
    protected $_COLUMNS = [];
    protected $_FROM = '';
    protected $_JOINS = '';
    protected $_WHERE =  '';
    protected $_GROUP_BY= '';
    protected $_HAVING = '';
    protected $_ORDER_BY = '';
    protected $_LIMIT = '';
    protected $_OFFSET = '';
    protected $_SETTERS = '';

    protected $_query = "";
    protected $_binds = "";
    protected $_params = [];

    private $_className = 'stdClass';

    function __construct()
    {
        \App\core\System::log('notice', 'New Query Builder class initiated');
    }
    
    public function from(string $table, string $alias = '') : self {
        $alias = ( $alias === '' ?  '' : ' AS '.$alias);
        $this->_FROM = " FROM {$table} {$alias} ";
        return $this;
    }

    public function join(string $table, string $alias = '', string $on = "", string $join_type = "INNER") {
        $alias = ( $alias === '' ?  '' : ' AS '.$alias);
        $this->_JOINS .= " {$join_type} JOIN {$table} {$alias} ON {$on} ";
        return $this;
    }

    public function orderBy(string $columnsList, string $order = 'ASC' ) : self {
        $this->_ORDER_BY = " ORDER BY {$columnsList} {$order} ";
        return $this;
    }

    public function limit(int $limit = 1000, int $offset = 0) : self {
        $this->_LIMIT = " LIMIT {$limit} ";
        $this->_OFFSET = " OFFSET {$offset} ";
        return $this;
    }


    public function where(string $column , $value, string $operand = "=") : self {
        $CLAUSE = $this->_WHERE === '' ? ' WHERE ' : ' AND ';
        $this->_WHERE .= $CLAUSE." `{$column}` {$operand} ? ";
        $this->_binParamFormat($value);
        $this->_params[] =  $value;
        return $this;
    }

    public function whereOr(string $column , $value, string $operand = "=") : self {
        $CLAUSE = $this->_WHERE === '' ? ' WHERE ' : ' AND ';
        $this->_WHERE .= $CLAUSE." `{$column}` {$operand} ? ";
        $this->_binParamFormat($value);
        $this->_params[] =  $value;
        return $this;
    }

    public function whereIn(string $column, array $assoc) : self {
        $CLAUSE = $this->_WHERE === '' ? ' WHERE ' : ' AND ';
        $this->_WHERE .= $CLAUSE." `{$column}` IN ? ";
        $this->_binds .= 's';
        $this->_params[] = '('.implode(",", array_values($assoc)).')';
        return $this;
    }

    public function whereNotIn(string $column, array $assoc) : self {
        $CLAUSE = $this->_WHERE === '' ? ' WHERE ' : ' AND ';
        $this->_WHERE .= $CLAUSE." `{$column}` NOT IN ? ";
        $this->_binds .= 's';
        $this->_params[] = '('.implode(",", array_values($assoc)).')';
        return $this;
    }

    public function groupBy(string $column) : self {
        $this->_GROUP_BY = "GROUP BY `{$column}` ";
        return $this;
    }

    public function having(string $column , $value, string $operand = "=") : self {
        $CLAUSE = $this->_HAVING === '' ? ' HAVING ' : ' AND ';
        $this->_HAVING .= $CLAUSE." `{$column}` {$operand} ? ";
        $this->_binParamFormat($value);
        $this->_params[] = is_array($value) ? '('.implode(",", array_values($value)).')' : $value;
        return $this;
    }

    public function set(string $column, $value ) : self {
        $CLAUSE = $this->_SETTERS === '' ? ' SET ' : ' , ';
        $this->_SETTERS .= $CLAUSE." `{$column}` = ? ";
        $this->_binParamFormat($value);
        $this->_params[] = is_array($value) ? '('.implode(",", array_values($value)).')' : $value;
        return $this;
    }

    public function select(array $fields = ["*"], bool $mode = false) : self {
        $this->_MODE =  1;
        if(count($fields) == 1 && $fields[0] == "*")  {
            $this->_query = "SELECT * ";
        } else {
            $select = '';
            foreach($fields as $index => $field) {
                if(is_numeric($index)) {
                    $this->_COLUMNS[] = $field;
                    if($mode === false) 
                        $select .= "`,{$field}`";
                    else
                        $select .= ",{$field}";
                } else {
                    $this->_COLUMNS[] = $index;
                    $select .= ",{$index}";
                }
            }
            $select = ltrim($select, ',');
            $this->_query = "SELECT {$select} ";
        }  
        return $this;
    }

    public function insert(string $table, array $data = []) : self {
        $this->_MODE = 2;
        $this->_query = "INSERT INTO {$table} ";
        $columnsList = "";
        $valuesList = "";
        foreach($data as $column => $value) {
            $column = trim($column);
            $columnsList .= ",`{$column}` ";
            $valuesList .= ", ?";
            $this->_binParamFormat($value);
            $this->_params[] = $value;
        }
        $columnsList = \ltrim($columnsList, ",");
        $valuesList = \ltrim($valuesList, ",");
        $this->_query .= " ({$columnsList}) VALUES ({$valuesList}) ";
        return $this;
    }

    public function update(string $table, array $data = []) : self {
        $this->_MODE = 3;
        $this->_query = "UPDATE {$table}";
        if(\count($data) > 0) {
            foreach($data as $column => $value) {
                $column = trim($column);
                $this->set($column, $value);
            }
        }
        return $this;
    }

    public function delete(string $table = '') : self {
        $this->_query = "DELETE ";
        $this->_MODE = 4;
        return $this;
    }

    public function getQuery() : string {

        switch($this->_MODE) {
            case 1:
                $this->_query .= " {$this->_FROM} {$this->_JOINS} {$this->_WHERE} {$this->_GROUP_BY} {$this->_HAVING} {$this->_ORDER_BY} {$this->_LIMIT} {$this->_OFFSET} ";
                break;
            case 3: 
                $this->_query .= " {$this->_JOINS} {$this->_SETTERS} {$this->_WHERE} {$this->_GROUP_BY} {$this->_HAVING} {$this->_LIMIT} ";
                break;
            case 4:   
                $this->_query .= " {$this->_FROM} {$this->_JOINS} {$this->_WHERE} {$this->_GROUP_BY} {$this->_HAVING} {$this->_ORDER_BY} {$this->_LIMIT} ";     
                break;
        }
        return $this->_query;
    }

    public function getBindParamsFormat() : string {
        return $this->_binds;
    }

    public function getBindPrams() : array {
        return $this->_params;
    }

    public function flush() : self {
        $this->_MODE = 0;
        $this->_COLUMNS = [];
        $this->_FROM = '';
        $this->_JOINS = '';
        $this->_WHERE = '';
        $this->_GROUP_BY = '';
        $this->_HAVING = '';
        $this->_ORDER_BY = '';
        $this->_LIMIT = '';
        $this->_OFFSET = '';
        $this->_SETTERS = '';
        return $this;
    }

    protected function _binParamFormat(&$value) {
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

    /**
     * Get the value of _className
     */ 
    public function getClassName()
    {
        return $this->_className;
    }

    /**
     * Set the value of _className
     *
     * @return  self
     */ 
    public function setClassName($_className)
    {
        $this->_className = $_className;

        return $this;
    }
}