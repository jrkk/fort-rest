<?php
namespace App\ORM;

class EntityReflection {
    function __construct(array $configuration) {
        $this->bindProperties();
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
}