<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 26/12/14
 * Time: 19:06
 */

namespace tests;

require_once 'TestController.php';


use SwagFramework\Router;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    private function getRouter()
    {
        $router = new Router();

        $router->addRoute(new Route('/', array(
                    'method' => 'GET',
                'controller' =>'tests\TestController/index')));

        $router->addRoute(new Route('/connection', array(
                    'method' => 'GET',
                'controller' => 'tests\TestController/connection')));

        $router->addRoute(new Route('/action', array(
                    'method' => 'GET',
                'controller' => 'tests\TestController/useraction')));

        return $router;
    }

    public function matchProvider()
    {
        $router = $this->getRouter();
        return array(
            array($router, '', true),
            array($router, '/', true),
            array($router, '/pagequinexistepas', false),
            array($router, '/index', true),
            array($router, '/action', true),
            array($router, '/connection', true),
            array($router, '/action/mdr', true)
        );
    }

    /**
     * @dataProvider matchProvider
     */

    public function testRoute($router, $path, $expected)
    {
        $this->assertEquals($router->match($path), $expected);
    }


}
