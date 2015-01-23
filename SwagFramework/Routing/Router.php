<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 27/12/14
 * Time: 15:36
 */
namespace SwagFramework\Routing;

use SwagFramework\Exceptions\RoutingException;

class Router
{
    /**
     * The route in the URI
     * @var Route
     */
    private $currentRoute;

    /**
     * An array that contains all the routes for this application
     * @var Route[]
     */
    private $routes = [];

    public function __construct($routes = [])
    {
        $this->routes = $routes;
    }

    public static function getSupportedHttpMethods()
    {
        return ['POST', 'PUT', 'DELETE'];
    }

    /**
     * Match the current request into the current Route
     * and call match() to find the correct route in routes
     */
    public function matchCurrentRequest()
    {
        $path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
        $method = $_SERVER["REQUEST_METHOD"];

        $this->currentRoute = new Route();
        $this->currentRoute->parseUrl($path, $method);

        $route = $this->match();

        /* If the route doesn't match, it will reformat the wrong route
        *  in order to "redirect" to the error404 page (/errors/err404)
        */

        if ($route == false) {
            $this->currentRoute = new Route();
            $this->currentRoute->parseUrl('/errors/err404');
            $route = $this->match();
        }

        $this->dispatch($route);
    }

    /**
     * This function looks for the route in the URI and returns it
     * if there is no route matching with the URI it returns false.
     *
     * @return Route
     * @throws RoutingException
     */
    private function match()
    {
        if (empty($this->routes)) {
            throw new RoutingException('There is no route saved !');
        }

        foreach ($this->routes as $route) {
            if ($route->getMethod() != $this->currentRoute->getMethod()) {
                continue;
            }

            // If the url doesn't begin with the url $route
            $url = ($this->currentRoute->getUrl() == '/') ? '/' : substr($this->currentRoute->getUrl(), 1);
            $urlb = ($route->getUrl() == '/') ? '/' : substr($route->getUrl(), 1);

            if (!preg_match('@^' . $urlb . '*@', $url)) {
                continue;
            }

            // Fix default route bug :)
            if ($urlb == '/' && $url != $urlb) {
                continue;
            }

            return $route;
        }
        return null;
    }

    /**
     * This method call the right function in the controller
     * targeted by the route with the parameters in the URI
     * @param Route $route
     */
    private function dispatch($route)
    {
        $action = $route->getAction();
        $route->getController()->setParams($this->currentRoute->getParameters());
        $route->getController()->$action();
    }

    /**
     * This function add a route to the $routes that contains all the routes
     * for this application.
     * @param $url
     * @param $controller
     * @param $action
     * @param $method
     */
    public function add($url, $controller, $action, $method = 'GET')
    {
        $route = new Route();
        $route->setRoute($url, $controller, $action, $method);

        $this->routes[] = $route;
    }

    public function displayRoute()
    {
        var_dump($this->currentRoute);
    }
}