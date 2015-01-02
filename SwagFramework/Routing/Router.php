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
    private $route;

    /**
     * An array that contains all the routes for this application
     * @var array
     */
    private $routes = array();

    public function __construct($routes = array())
    {
        $this->routes = $routes;
    }

    /**
     * Match the current request into the current Route
     * and call match() to find the correct route in routes
     */
    public function matchCurrentRequest()
    {
        $path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
        $method = $_SERVER["REQUEST_METHOD"];

        $this->route = new Route();
        $this->route->parseUrl($path, $method);

        $route = $this->match();

        /* If the route doesn't match, it will reformat the wrong route
        *  in order to "redirect" to the home page (/)
        */
        if ($route == false) {
            $this->route = new Route();
            $this->route->parseUrl();
            $this->match();
        } else {
            $this->dispatch($route);
        }
    }

    /**
     * This function looks for the route in the URI and returns it
     * if there is no route matching with the URI it returns false.
     *
     * @return bool
     * @throws RoutingException
     */
    private function match()
    {
        if (empty($this->routes)) {
            throw new RoutingException('There is no route saved !');
        }

        foreach ($this->routes as $route) {
            if ($route->getMethod() != $this->route->getMethod()) {
                continue;
            }

            // If the url doesn't begin with the url $route
            if (!preg_match('@^' . $route->getUrl() . '*@', $this->route->getUrl())) {
                continue;
            }

            return $route;
        }
        return false;
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

    /**
     * This method call the right function in the controller
     * targeted by the route with the parameters in the URI
     * @param Route $route
     */
    private function dispatch($route)
    {
        $action = $route->getAction();
        $route->getController()->setParams($this->route->getParameters());
        $route->getController()->$action();
    }
}