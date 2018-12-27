<?php
namespace App\ORM;

use App\Prototype\Database;

class Entity {

    private $properties = [];
    private $primaryKey = [];
    private $table = '';

    private $db = null;
    function __construct(Database &$db) {
        $this->db = $db;
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
        print_r($this->table);
        print_r($this->properties);
    }
    
    public function find() {

    }

    public function findById() {

    }
    
    public function save() {

    }

    public function update() {

    }

    public function updateById() {

    }

    public function remove() {
        throw new \Exeception('Delete records in Database is not allowed now');
    }

}