<?php
namespace App\Core;

class Header extends Container {

    function __construct()
    {
        System::log('info','Header Object intiated');
    }

    public function parse(array $headers) {
        foreach($headers as $key => $value) {
            $this->wrapper[$key] = $value;
        }
    }

    public function getAll() {
        return $this->wrapper;
    }

}