<?php
namespace App\Entity;

class Route {

    protected $name = '';
    protected $target = '';
    protected $controller = '';
    protected $method = '';
    protected $methods = ['GET','POST'];
    protected $origins = ['*'];
    protected $schemes = ['http','https'];
    protected $regx = '';
    protected $matches = [];

    function __construct(
        string $name,
        string $target,
        string $controller,
        string $method,
        array $methods = [],
        array $origins = [],
        array $schemes = [],
        string $regx = '',
        array $vars = []
    ){
        $this->name = $name;
        $this->target = $target;
        $this->controller = $controller;
        $this->method = $method;
        !empty($methods) && $this->methods = $methods;
        !empty($origins) && $this->origins = $origins;
        !empty($schemes) && $this->schemes = $schemes;
        !empty($regx) && $this->regx = $regx;
        !empty($vars) && $this->matches = array_merge($this->matches, $vars);
    }

}