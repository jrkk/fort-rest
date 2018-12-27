<?php
namespace App\ORM;

use App\Prototype\Database;
use App\Driver\Mysql\Query;

class Entity {

    protected $properties = [];
    protected $primaryKey = [];
    protected $table = '';

    protected $_query = "";
    protected $_binds = "";
    protected $_data = [];


    private $db = null;
    function __construct(Database &$db) {
        $this->db = $db;
        \App\Core\System::log('notice','Entity has initiated for '.get_class($this));
    }

    public function bindProperties() {
        $reflection = new \ReflectionClass($this);
        $classDocumentation = $reflection->getDocComment();
        $classDocumentation = str_replace(['/',"*","\t"],["","",""], $classDocumentation);
        $comments = explode("\n", $classDocumentation);
        foreach ($comments as $comment) {
            $annotation = ltrim($comment,' ');
            $annotation = ltrim($annotation,"\n");
            if(strstr($annotation, "@") === false )
                    continue;
            if(strstr($annotation, "@Table") !== false) {
                $this->table = str_replace(['@Table=',"\n"], ["",""], $annotation);
            }
        }
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $property) {
            $document = $property->getDocComment();
            $document = str_replace(['/',"*","\t"],["","",""], $document);
            $comments = explode("\n", $document);
            $propertyConfiguration = [];
            foreach ($comments as $comment) {
                $annotation = ltrim($comment,' ');
                if(strstr($comment, "@") === false )
                    continue;
                if(strstr($annotation, "@Id") !== false) {
                    $propertyConfiguration["id"] = true;
                    $this->primaryKey[] = $property->getName();
                }
                if(strstr($annotation, "@AutoSequence") !== false) {
                    $propertyConfiguration["auto"] = true;
                }
                if(strstr($annotation, "@Nullable") !== false) {
                    $propertyConfiguration["Nullable"] = true;
                }
                if(strstr($annotation, "@Column") !== false) {
                    $propertyConfiguration["column"] = str_replace(['@Column=',"\n"], ["",""], $annotation);
                }
                if(strstr($annotation, "@Type") !== false) {
                    $propertyConfiguration["class"] = str_replace(['@Type=',"\n"], ["",""], $annotation);
                }
            }
            if(!isset($propertyConfiguration["column"])) {
                $propertyConfiguration["column"] = $property->getName();
            }
            if( isset($propertyConfiguration["class"])
                    && in_array($propertyConfiguration["class"], ['int','string','char','float']))
                $propertyConfiguration['premitive'] = true;
            else {
                $propertyConfiguration["class"] = gettype($this->{$property->getName()});
                $propertyConfiguration['premitive'] = false;
            }
            $propertyConfiguration["default"] = $this->{$property->getName()};
            $this->properties[$property->getName()] = $propertyConfiguration;
        }
    }
    
    public function find() {

    }

    public function findById() {

    }
    
    public function save() {
        $binds = "";
        $query = "INSERT INTO {$this->table} ";
        $columnsList = "";
        $valuesList = "";
        foreach($this->properties as $column => $configuration) {
            $columnsList .= ",`{$column}`";
            $valuesList .= ",?";
            $this->_binParamFormat($this->{$column}, $binds);
            $data[] = $this->{$column};
        }
        $columnsList = ltrim($columnsList, ",");
        $valuesList = ltrim($valuesList, ",");
        $query .= "({$columnsList}) VALUES ({$valuesList})";
        var_export([$query, $binds, $data]);
    }

    public function update() {
        $binds = "";
        $query = "UPDATE {$this->table} ";
        $setterList = "";
        foreach($this->properties as $column => $configuration) {
            $setterList .= ",`{$column}` = ?";
            $this->_binParamFormat($this->{$column}, $binds);
            $data[] = $this->{$column};
        }
        $setterList = ltrim($setterList, ",");
        $query .= "SET ({$setterList}) WHERE {$this->primaryKey['0']} = ?";
        $this->_binParamFormat($this->{$this->primaryKey[0]}, $binds);
        $data[] = $this->{$this->primaryKey[0]};
        var_export([$query, $binds, $data]);
    }

    protected function _binParamFormat(&$value, &$binds) {
        if( is_int($value) || is_bool($value) ) {
            $binds .= "i";
        } else if ( is_float($value) ) {
            $binds .= "d";
        } else if ( is_string($value) && strlen($value) < 4096 ) {
            $binds .= "s";
        } else {
            $binds .= "b";
        }
    }

    public function updateById() {

    }

    public function remove() {
        throw new \Exeception('Delete records in Database is not allowed now');
    }

}