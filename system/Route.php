<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 27/12/14
 * Time: 00:22
 */

namespace SwagFramework;


class Route
{

    /**
     * URL of the route
     * @var string
     */
    private $url;

    /**
     * Target for the route
     * @var mixed
     */
    private $target;

    /**
     * Name of the route (for reverse routing)
     * @var string
     */
    private $name;
    /**
     * HTTP methods accepted for the route
     * @var array
     */
    private $methods = array('GET', 'POST');

    /**
     * Contains all the parameters for the route
     * @var array
     */
    private $parameters = array();

    /**
     * @param $url
     * @param $config
     */
    public function __construct($url, $config)
    {
        $this->url = $url;
        $this->config = $config;
        $this->methods = isset($config['methods']) ? $config['methods'] : array();
        $this->targer = isset($config['target']) ? $config['target'] : null ;
    }

    public function getUrl(){ return $this->url; }
    public function getTarget(){ return $this->target; }
    public function getMethods(){ return $this->methods; }
    public function getParameters(){ return $this->parameters; }
    public function getName(){ return $this->name; }

    public function setMethods($methods){ $this->methods = $methods; }
    public function setParameters($parameters){ $this->parameters = $parameters; }

    public function setUrl($url)
    {
        // We never know ...
        $url = strval($url);

        // Put a / at the end of the url if there isn't one
        if(substr($url, -1) != '/')
            $url .= '/';

        $this->url = $url;
    }

    public function getRegex()
    {
        return preg_replace_callback("/:(\w+)/", array(&$this, 'substituteFilter'), $this->url);
    }

    private function substituteFilter($matches)
    {
        if (isset($matches[1]) && isset($this->_filters[$matches[1]])) {
            return $this->_filters[$matches[1]];
        }
        return "([\w-%]+)";
    }

    public function dispatch()
    {
        $action = explode('/', $this->config['controller']);
        $instance = new $action[0];
        call_user_func_array(array($instance, $action[1]), $this->parameters);
    }
}