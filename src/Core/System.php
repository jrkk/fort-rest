<?php
namespace App\Core;

use App\Config\AppConfig;
use App\Driver\Mysql\Native as DB;

use App\Exception\ClassNotFoundException;

class System {

    public static $logger;
    public static $startAtTm = 0;

    public static $OC = null;

    public static function Init() : void {
        self::$startAtTm = microtime(true);
        $logger = AppConfig::Logger;
        self::$logger = new $logger();
        self::$logger->info("Logger initiated");
        AppConfig::MySqlDriver && DB::connect();
        self::$OC = new Container();
    }

    public static function log($mode, $message, $data = []) {
        if(self::$logger instanceof \App\Driver\Logger\BaseLogger ) {
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

    public static function load(string $name = '', string $class = null) : object {
        if(self::$OC->has($name)) {
            return self::$OC->get($name);
        }
        if(!empty($class)) {
            $object = new $class(); 
            self::$OC->share($name, $object);
            return $object;
        }
        throw new ClassNotFoundException($class);
    } 

    public static function Stop() : void {
        AppConfig::MySqlDriver && DB::close();
        $endAt = microtime(true);
        self::$logger->notice("Process completed in :".($endAt-self::$startAtTm));
        self::$logger->push('process');
    }

}