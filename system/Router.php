<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 17/12/14
 * Time: 08:46
 */

namespace SwagFramework;


class Router
{

    /**
     * Array that store all the routes
     * @var array
     */
    private $routes = array();

    /**
     * Base REQUEST_URI
     * @var string
     */
    private $basePath = '';

    /**
     * Array that store all the named routes (for reverse routing)
     * @var array
     */
    private $namedRoutes = array();

    /**
     * @param RouteCollection $collection
     */
    public function __construct(RouteCollection $collection)
    {
        $this->routes = $collection;
    }
    /*
     * Set the base REQUEST_URI
     * @param $basePath
     */
    public function setBasePath($basePath){ $this->basePath = $basePath; }

    /*
     * Return the $routes :)
     */
    private function getRoutes(){ return $this->routes; }

    /**
     * Matches the currect request with a route
     */
    public function matchCurrentRequest()
    {
        $requestMethod = (isset($_POST['method']) && ($method = strtoupper($_POST['method']))) ? $method : $_SERVER['REQUEST_METHOD'];
        $requestUrl = $_SERVER['REQUEST_URI'];

        // get GET variables from URL
        if(($pos = strpos($requestUrl, '?')) != false)
            $requestUrl = substr($requestUrl, 0, $pos);

        return $this->match($requestUrl, $requestMethod);
    }

    /**
     * Find if the $url for the $method has a route and returns it if it is the case
     * if not return false :)
     * @param $url
     * @param string $method
     * @return bool
     */
    public function match($url, $method = 'GET')
    {
        foreach($this->routes as $route)
        {
            // Check if the method for the route is in the allowed http methods
            if($route['method'] != $method)
                continue;

            // Check if the requested url matches the route regex
            if(!preg_match("@^".$this->basePath.$route->getRegex()."*$@i", $url, $matches))
                continue;

            $params = array();

            if(preg_match_all("/:([\w-%]+)/", $route->getUrl(), $argument_keys))
            {
                // Get the array that matches with the previous regex
                $argument_keys = $argument_keys[1];

                foreach($argument_keys as $key => $name)
                    if(isset($matches[$key + 1]))
                        $params[$name] = $matches[$key + 1];

            }

            $route->setParameters($params);
            $route->dispatch();

            return $route;
        }

        return false;
    }

    /**
     * Reverse routing (with namedRoutes)
     * @param $routeName
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function generate($routeName, $params = array())
    {

        // Check if the route exists, throw an exception if it's not
        if(!isset($this->namedRoutes[$routeName]))
            throw new Exception('There is no route with the name ' . $routeName);

        // Else
        $route = $this->namedRoutes[$routeName];
        $url = $route->getUrl();

        // Replace route url with given parameters
        if($params && preg_match_all("/:(\w+)/", $url, $param_keys))
        {
            $param_keys = $param_keys[1];

            // Loop through parameters and create the right url
            foreach($param_keys as $key)
                if(isset($params[$key]))
                    $url = preg_replace("/:(\w+)/", $params[$key], $url, 1);
        }

        return $url;
    }

}