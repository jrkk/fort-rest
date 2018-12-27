<?php
namespace App\Helper;

trait PropertiesBinder {
    private function bindConfiguration() {
        if(!isset($this->config)) return false;
        foreach($this->config as $index => $value ) {
            if(isset($this->{$index}) 
                && gettype($this->{$index}) === gettype($value) ) {
                $this->{$index} = $value;
            }
        }
    }
}