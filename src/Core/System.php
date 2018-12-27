<?php
namespace App\Core;

class System {

    public static $logger;

    public static function Init() {
        self::$logger = new \App\Driver\Logger\FileLogger();
    }

    public static function log($mode, $message, $data = []) {
        if(self::$logger instanceof \App\Driver\Logger\Log ) {
            self::$logger->{$mode}($message, $data);
        }
    }   
    
    public static function Start() {

        $uri = new Uri();

        $requestHeader = new Header();
        $responseHeader = new Header();

        $server = new Server();

        $request = new Request($requestHeader);
        $request->withUri($uri)
                ->withServer($server);
        $response = new Response($responseHeader);

        $router = new Router();
        $target = $router->getRoute($request, $response);
        if($target === false) {
            return $response->send();
        }
        
        $ctrl = "\\App\\Controller\\".$target['controller'];

        $controller = new $ctrl();
        $controller->inject($request, $response);
        return $controller->exec($target); 

    }

    public static function Stop() {
        self::$logger->push('process');
    }

}