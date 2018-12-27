<?php
namespace App\Core;

class System {

    public static $logger;
    public static $startAtTm = 0;

    public static function Init() {
        self::$startAtTm = microtime(true);
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
        $response = new Response($responseHeader);
        $router = new Router();
        
        $request->withUri($uri)
                ->withServer($server);
        
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
        $endAt = microtime(true);
        self::$logger->notice("Process completed in :".($endAt-self::$startAtTm));
        self::$logger->push('process');
    }

}