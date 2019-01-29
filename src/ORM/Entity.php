<?php
namespace App\ORM;

use App\Prototype\Database;
use App\Prototype\QueryBuilder;
use App\Driver\Mysql\Native as DB;
use App\Driver\Mysql\Query;

class Entity {

    private $_table = '';
    private $_nullables = [];
    private $_data_types = [];
    private $_properties = [];
    private $_keys = [];
    private $_squences = [];
    private $_auto_columns = [];
    private $_primitives = [];
    private $_changed_columns = [];

    function __construct(array $configuration = []) {
        $this->bindProperties();
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
                    $column = str_replace(['@Column=',"\n","\t","\r\n"], ["","","",""], $annotation);
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
    
    public function find(array $critera = []) 
    {
        $query =  new Query();
        if(isset($critera['select']))
            $query->select($critera['select']);
        $query->from($this->_table);
        if(isset($critera['where'])  
            && is_array($critera['where']) 
            && count($critera['where']) > 0 ) {
            foreach( $critera['where'] as $where ) {
                @call_user_func_array([$query, 'where'], ...$where);
            }
        }
        if(isset($critera['where'])  
            && is_array($critera['where']) 
            && count($critera['where']) > 0 ) {
            foreach( $critera['where'] as $where ) {
                @call_user_func_array([$query, 'where'], ...$where);
            }
        }
    }

    public function findOne(array $critera = [])
    {
        $critera['limit'] = 1;
        $critera['offset'] = 0;
        $row = $this->find($critera);  
        if($row == null) return false;
        $this->fromObject($row[0]);
        return true;
    }
    public function findById() : bool {
        $query = new Query();
        //$query->setClassName(get_class($this));
        $query->select()
            ->from($this->_table)
            ->where($this->_keys[0], $this->{$this->_keys[0]})
            ->limit(1,0)
            ;

        $row = DB::retrive($query);  
        if($row == null) return false;
        $this->fromObject($row);
        return true;
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
        $insertId = DB::persist($query);

        $this->{$this->_keys[0]} = $insertId;

        return $insertId > 0 ? true : false;

    }
    public function update(array $properties = [], QueryBuilder $query = null ) : bool {

        if(count($properties) <= 0) return false;
        $data = [];
        foreach ($properties as $property) {
            if(!isset($this->_properties[$property]) || \is_numeric($property) ) {
                throw new Exception('Property or Column name not found');
            }    
            $data[$this->_properties[$property]]  = $this->{$property};
        }

        if($query === null) $query = new Query();
        $query->update($this->_table, $data)->where($this->_keys[0], $this->{$this->_keys[0]});

        $affected = DB::update($query);

        return $affected > 0 ? true : false;
    }
    public function remove() : bool {
        $query = new Query();
        $query->delete()->from($this->_table)->where($this->_keys[0], $this->{$this->_keys[0]});

        $affected = DB::remove($query);

        return $affected > 0 ? true : false;
    }
<<<<<<< HEAD
    public function fromObject(Object $row) {
=======
    public function fromObject(stdClass $row) {
>>>>>>> 02c1985ff42380947d31cfd61797ff98d46e824f
        foreach ($row as $column => $value ) {
            if(isset($this->{$column}) && in_array($column, $this->_properties) )
                $this->{$column} = $value;
        }
        return $this;
    }

    public function fromArray(array $data) {
        foreach ($data as $column => $value ) {
            if(isset($this->{$column}) && in_array($column, $this->_properties) )
                $this->{$column} = $value;
        }
        return $this;
    }

}