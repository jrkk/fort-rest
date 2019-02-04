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

        self::load('uri', Uri::class);
        //self::load('requestHeader', Header::class);
        //self::load('responseHeader', Header::class);
        self::load('server', Server::class);
        //self::load('request', Request::class, ['requestHeader']);
        //self::load('response', Response::class, ['responseHeader']);
        self::load('request', Request::class, ['requestHeader' => Header::class]);
        self::load('response', Response::class, ['responseHeader' => Header::class]);
        self::load('router', Router::class);

        self::$logger->info("System Resources has loaded");

        if(count(AppConfig::PreLoads) > 0) {
            foreach (AppConfig::PreLoads as $resource) {
                self::load(...$resource);
            }
        }

        self::$logger->info("Pre Loading Resources has loaded");

    }

    public static function log($mode, $message, $data = []) {
        if(self::$logger instanceof \App\Driver\Logger\BaseLogger ) {
            self::$logger->{$mode}($message, $data);
        }
    }   
    
    public static function Start() {

        $request = self::load('request');
        $response = self::load('response');
        
        $request->withUri(self::load('uri'))
                ->withServer(self::load('server'));
        
        $security = self::load('security', Security::class);
        $router = self::load('router');

        $target = $router->getRoute($request, $response);
        if($target === false
            || $router->validate($target) === false ) {
            return $response->send();
        }
        
        $ctrl = "\\App\\Controller\\".$target['controller'];

        $controller = new $ctrl();
        return $controller->exec($target['method']); 

    }

    public static function load(string $name, string $class = null, array $params = []) : object {
        if(self::$OC->has($name)) {
            return self::$OC->get($name);
        }
        $dependencies = [];
        if(count($params) > 0 ){
            foreach ($params as $definition => $dependency ) {
                if(is_int($definition)
                    && self::$OC->has($dependency)) {
                    $dependencies[] = self::$OC->get($dependency);
                } else {
                    if(self::$OC->has($definition)) {
                        $dependencies[] = self::$OC->get($definition);
                    } else {
                        $resolved = new $dependency(); 
                        self::$OC->share($definition, $dependency);
                        $dependencies[] = $resolved;
                    }
                }
            }
        }
        if(!empty($class)) {
            $object = new $class(...$dependencies); 
            self::$OC->share($name, $object);
            return $object;
        }
        throw new ClassNotFoundException($name.'-->'.$class);
    } 

    public static function Stop() : void {
        AppConfig::MySqlDriver && DB::close();
        $endAt = microtime(true);
        self::$logger->notice("Process completed in :".($endAt-self::$startAtTm));
        self::$logger->push('process');
    }

}