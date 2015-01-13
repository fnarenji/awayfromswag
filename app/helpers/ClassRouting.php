<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 13/01/15
 * Time: 16:02
 */

namespace app\helpers;

/**
 * Class ClassRouting
 * This function generate basics routes for a list of classes
 * @package app\helpers
 */
class ClassRouting
{

    /**
     * An array of all the classes we want to be generated
     * @var array
     */
    private $classes = array();

    /**
     * The namespace of the class (should be like \foo\bar\ or \foo\)
     * @var string
     */
    private $namespace;

    /**
     * Initialize the class
     * @param array $classes
     * @param string $namespace
     */
    public function __construct($namespace = '', $classes = array())
    {
        $this->setNamespace($namespace);
        $this->setClasses($classes);
    }

    private function setClasses($classes)
    {
        $this->classes = $classes;
    }

    public function addClass($class)
    {
        $this->classes[] = $class;
    }

    private function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }


    /**
     * Add all the routes in the router in parameter
     * @param $router
     */
    public function generateRoute($router)
    {
        foreach ($this->classes as $class)
        {
            $classMethods = get_class_methods($this->namespace . $class . 'Controller');
            $rc = new \ReflectionClass($this->namespace . $class . 'Controller');

            $parent = $rc->getParentClass();
            $parent = get_class_methods($parent->name);

            $className = $this->namespace . $class . 'Controller';

            foreach ($classMethods as $methodName)
            {
                if (in_array($methodName, $parent) || $methodName == 'index')
                    continue;
                else
                    $router->add('/' . strtolower($class) . '/' . $methodName, new $className(), $methodName);
            }
            $router->add('/' . strtolower($class), new $className(), 'index');
        }

    }
}