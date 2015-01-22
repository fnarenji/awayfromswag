<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 13/01/15
 * Time: 16:02
 */

namespace app\helpers;

use SwagFramework\Routing\Router;

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

    private function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    private function setClasses($classes)
    {
        $this->classes = $classes;
    }

    public function addClass($class)
    {
        $this->classes[] = $class;
    }

    /**
     * Add all the routes in the router in parameter
     * @param $router Router
     */
    public function generateRoute(Router $router)
    {
        foreach ($this->classes as $class) {
            $classMethods = get_class_methods($this->namespace . $class . 'Controler');
            $rc = new \ReflectionClass($this->namespace . $class . 'Controler');

            $parent = $rc->getParentClass();
            $parent = get_class_methods($parent->name);

            $className = $this->namespace . $class . 'Controler';

            foreach ($classMethods as $methodName) {
                if (in_array($methodName, $parent) || $methodName == 'index') {
                    continue;
                } else {
                    $router->add('/' . strtolower($class) . '/' . $methodName, new $className(), $methodName);
                }
            }
            $router->add('/' . strtolower($class), new $className(), 'index');
        }

    }
}