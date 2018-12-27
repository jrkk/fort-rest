<?php
namespace App\Core;

use App\Config\Routes;
use App\Config\AppConfig;

class Router
{

    protected $routing = null;

    function __construct()
    {
        $path = dirname(FCPATH) . '/src/Config/Routes.php';
        $this->routing = include_once $path;

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

}