<?php
namespace App\ORM;

use App\Prototype\Database;
use App\Driver\Mysql\Native as DB;
use App\Driver\Mysql\Query;

class Entity {

    protected $_table = '';
    protected $_nullables = [];
    protected $_data_types = [];
    protected $_properties = [];
    protected $_keys = [];
    protected $_squences = [];
    protected $_auto_columns = [];
    protected $_primitives = [];
    protected $_changed_columns = [];

    function __construct() {
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
                $this->_table = str_replace(['@Table=',"\n"], ["",""], $annotation);
            }
        }
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $property) {
            $document = $property->getDocComment();
            $document = str_replace(['/',"*","\t"],["","",""], $document);
            $comments = explode("\n", $document);
            $propertyConfiguration = [];
            foreach ($comments as $comment) {
                
                $name = '';
                $name = $property->getName();

                $annotation = ltrim($comment,' ');
                if(strstr($comment, "@") === false )
                    continue;
                if(strstr($annotation, "@Id") !== false) {
                    $propertyConfiguration["id"] = true;
                    $this->_keys[] = $name;
                }
                if(strstr($annotation, "@AutoSequence") !== false) {
                    $_auto_columns[] = $name;
                }
                if(strstr($annotation, "@Nullable") !== false) {
                    $this->_nullables[] = $name;
                }
                if(strstr($annotation, "@Column") !== false) {
                    $column = str_replace(['@Column=',"\n","\t"], ["","",""], $annotation);
                    $this->_properties[$name] = $column;
                } 
                if(strstr($annotation, "@Type") !== false) {
                    $this->_data_types[$name] = str_replace(['@Type=',"\n"], ["",""], $annotation);
                }
            }
            if(!isset($this->_properties[$name])){
                $this->_properties[$name] = $name;
            }
            if( isset($this->_data_types[$name])
                    && in_array($this->_data_types[$name], ['int','string','char','float']))
                $this->_primitives[] = $name;
            else {
                $this->_data_types[$name] = gettype($this->{$name});
                if(in_array($this->_data_types[$name], ['int','string','char','float']))
                    $this->_primitives[] = $name;
            }
        }
    }
    
    public function find(array $critera = []) {
        $query = new Query();
        if(!isset($critera['select']) || empty($critera['select']) ) {

        }
    }

    public function findById() {
        $query = new Query();
        $query->select('*')
            ->where($this->_keys[0], $this->{$this->_keys[0]})
            ->limit(1,0)
            ;
    }
    
    public function save() : bool {
        $data = [];
        foreach ($this->_properties as $property => $column) {
            if( \in_array($property, $this->_keys) 
                || \in_array($property, $this->_nullables) ) continue;
            if(!empty($this->{$property}))
                $data[$column] = $this->{$property};
        }

        $query = new Query();
        $query->insert($this->_table, $data);

        $insertId = 0;
        $insertId = DB::persist(
            $query->getQuery(), 
            $query->getBindParamsFormat(), 
            $query->getBindPrams()
        );

        $this->{$this->_keys[0]} = $insertId;

        return $insertId > 0 ? true : false;

    }

    public function update(array $properties = [], Query $query = null ) : bool {

        if(count($properties) <= 0) return false;
        $data = [];
        foreach ($properties as $property) {
            if(!isset($this->_properties[$property]) || \is_numeric($property) ) {
                throw new Exception('Property or Column name not found');
            }    
            $data[$this->_properties[$property]]  = $this->{$property};
        }

        if($query === null) {
            $query = new Query();
            $query->where($this->_keys[0], $this->{$this->_keys[0]});
        }

        $query->update($this->_table, $data);

        $affected = DB::update(
            $query->getQuery(), 
            $query->getBindParamsFormat(), 
            $query->getBindPrams()
        );

        return $affected > 0 ? true : false;
    }

    public function remove() {
        
    }

}