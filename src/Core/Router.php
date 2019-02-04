<?php
namespace App\Core;

use App\Config\Routes;
use App\Config\AppConfig;

class Router
{
    use \App\Helper\Loader;

    protected $routing = null;

    function __construct()
    {
        $this->routing = $this->loadConfig('/src/Config/Routes.php');
        System::log('info', 'Router class initiated');

    }

    public function getRoute(Request &$request, Response &$response)
    {

        $path = $request->getUri()->getPath();
        $path = str_replace(AppConfig::APPDIR, '', $path);
        if ($path == '' || $path == '/')
            $path = 'default';

        // find the route in routes list
        if (isset($this->routing->routes[$path])) {
            $target = $this->routing->routes[$path];
        } else {
            foreach ($this->routing->routes as $route) {
                $pattern = '/^' . addcslashes($route['regx'], '/') . '$/';
                $pattern = str_replace([':num'], ['(\d)+'], $pattern);
                if (isset($route['regx'])
                    && $route['regx'] != ''
                    && preg_match($pattern, $path, $route['matches'])) {
                    $target = $route;
                    break;
                }
            }
        }

        // is route found or not
        if (empty($target)) {
            $response->sendError(404, 'Resource not found to delivery');
            return false;
        }
        
        return $target;
    }

    public function validate(array &$route) {

        $security = System::load('security', Security::class);
        $request = System::load('request', Request::class);
        $response = System::load('response', Response::class);

        if($security->isAllowedMethod($request, $route['allowedMethods']) === false) {
            System::$logger->info("method not allowed");
            $response->sendError(405, 'method not found');
            return false;
        }

        if($security->isAllowedOrigin($request, $route['allowedOrigins']) === false ) {
            System::$logger->info("Origin not allowed");
            $response->sendError(400, 'Bad Request');
            return false;
        }

        if($security->isAllowedProtocol($request, $route['allowedSchemes']) === false ) {
            System::$logger->info("Un-Supported Scheme");
            $response->sendError(505, 'Un-Supported Scheme');
            return false;
        }

        return true;

    }

}