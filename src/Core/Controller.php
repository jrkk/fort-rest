<?php
namespace App\Core;

class Controller
{
    protected $request = null;
    protected $response = null;

    protected $isAuthorized = false;

    use \App\Helper\Oauth;

    function __construct() {
        System::log('info', 'Controller class initiated');
    }

    public function inject(
        Request &$request,
        Response &$response
    )
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function exec($method, array $params = []) {

        $this->request = System::load('request');
        $this->response = System::load('response');

        $this->request->getServer()->parse();

        
        call_user_func_array([$this, $method], $params);

        return $this->response->send();

    }
}