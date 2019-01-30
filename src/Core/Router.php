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

}