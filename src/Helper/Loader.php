<?php
namespace App\Helper;

trait Loader {

    /**
     * returns the BaseConfig Interface Object
     */
    protected function getConfig($class) {
        $config =  new $class();
        return $config->getAllConfig();
    }

    /**
     * Load configuration is nothing but 
     * loads the file and get configuration content.
     */
    protected function loadConfig($file) {
        try {
            return include_once BASEPATH.$file;
        } catch ( Exception $e ) {
            throw new Exception("Configuration not loaded: ".$e->getMessage());
        }
    }



}