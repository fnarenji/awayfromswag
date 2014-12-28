<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 27/12/14
 * Time: 15:38
 */

namespace SwagFramework\Routing;

class Route
{

    /**
     * The address of the route
     * @var string
     */
    private $url;

    /**
     * The controller targeted by this route
     * @var string
     */
    private $controller;

    /**
     * The method for the controller called by the url
     * @var string
     */
    private $action;

    /** The HTTP method of the route (in generally GET or POST)
     * @var string
     */
    private $method;

    /**
     * The parameters for the route's controller method
     * @var array
     */
    private $parameters = array();


    /**
     * This method extract from an url all the informations
     * possible in order to retrieve the right route later
     * @param string $url
     * @param string $method
     */
    public function parseUrl($url = '/', $method = 'GET')
    {
        $this->url = $url;

        // For the homepage :)
        if($url !== '/')
        {
            $path = trim($url, '/');
            $path = explode('/', $path);

            $this->controller = $path[0];
            $this->action = $path[1];
            $this->parameters = array_splice($path, 2, count($path) - 1);
        }

        $this->method = $method;
    }

    /**
     * This method set a Route with the parameters
     * @param $url
     * @param $controller
     * @param $action
     * @param string $method
     */
    public function setRoute($url, $controller, $action, $method = 'GET')
    {
        $this->url = $url;
        $this->controller = $controller;
        $this->action = $action;
        $this->method = $method;
    }

    public function getUrl(){ return $this->url; }
    public function getController(){ return $this->controller; }
    public function getAction(){return $this->action; }
    public function getParameters(){ return $this->parameters; }
    public function getMethod(){ return $this->method; }

}