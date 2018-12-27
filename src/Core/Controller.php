<?php
namespace App\Core;

use App\Driver\Mysql\Native;

class Controller
{

    function __construct() {

    }

    protected $request = null;
    protected $response = null;

    protected $db = null;

    public function inject(
        Request $request,
        Response $response
    )
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function exec(array $target) {

        $this->request->getServer()->parse();

        // is valid request method
        if (!empty($target)) {
            $method = $this->request->getMethod(true);
            if(!in_array($method, $target['allowedMethods'])) {
                return $this->response->sendError(405, 'method not found')->send();
            }
        }

        // is valid origin
        if( !in_array("*", $target['allowedOrigins']) ) {
            $this->request->withRequestTarget($target['allowedOrigins']);
            $referer = $this->request->getServer()->getReferer();
            var_export($referer);
            if(!in_array($referer, $target['allowedOrigins'])) {
                return $this->response->sendError(400, 'Bad Request')->send();
            }
        }

        //is allowed scheme
        if(!in_array($this->request->getServer()->getProtocol(), $target['allowedSchemes'])) {
            return $this->response->sendError(505, 'Un-Supported Scheme')->send();
        }

        $this->db = new Native();
        $this->db->connect();

        call_user_func_array([$this, $target['method']],[]);

        $this->db->close();
        
        return $this->response->send();

    }

    public function load(string $resource, string $class, string $name) : void {
        switch($resource) {
            case 'entity' : 
                $this->{$name} = new $class($this->db); 
                $this->{$name}->bindProperties();
                break;
        }
    } 
}