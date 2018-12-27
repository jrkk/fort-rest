<?php
namespace App\Core;
use Psr\Container\ContainerInterface;

use App\Exception\NotFoundException;
use App\Exception\ContainerException;

class Container implements ContainerInterface  {

    protected $wrapper = [];

    public function get($id) {
        if(!is_string($id) || !isset($this->wrapper[$id])) {
            throw new ContainerException();
        }
        return $this->wrapper[$id];
    }

    public function has($id) {
        if(!is_string($id)) {
            throw new NotFoundException();
        }
        return isset($this->wrapper[$id]) ? true : false ;
    }

    public function set($id, $val) {
        $this->wrapper[$id] = $val;
        return $this;
    }

    public function remove($id) {
        if($this->has($id)) {
            unset($this->wrapper[$id]);
        }
    }

}