<?php
namespace App\Core;

class Server extends Container {

    function __construct()
    {
        System::log('info', 'Server class initiated');
    }
    
    public function parse() {
        foreach($_SERVER as $key => $value) {
            $this->wrapper[$key] = $value;
        }
    }

    public function attributes() {
        return $this->wrapper;
    }

    public function getReferer() {
        try {
            return $this->has('HTTP_REFERER') ? $this->get('HTTP_REFERER') : $this->get('REMOTE_ADDR');
        } catch ( \App\Exception\NotFoundException $nfe ) {
            return $this->get('REMOTE_ADDR');
        }
        return NULL;
    }

    public function getProtocol() {
        try {
            return $this->get('REQUEST_SCHEME');
        } catch ( \App\Exception\ContainerException $ce ) {
            return 'http';
        }

    }

}