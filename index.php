<?php

define('CR', "\n");
define('TAB', '    ');

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', __DIR__ . DS);
define('WEBROOT', dirname($_SERVER['SCRIPT_NAME']) . '/');

define('DEBUG', true);

if (DEBUG)
{
    ini_set('display_errors', true);
    ini_set('html_errors', true);
    error_reporting(E_ALL);
}

require 'vendor/autoload.php';

try
{
    $router = new \SwagFramework\Routing\Router(\SwagFramework\Config\DatabaseConfig::parseFromFile("app/config/database.json"));

    $classrouting = new \app\helpers\ClassRouting('\app\controllers\\');
    $classrouting->addclass('User');
    $classrouting->generateroute($router);

    $router->add('/errors/err404', new \app\controllers\ErrorsController(), 'err404');
    $router->add('/', new \app\controllers\HomeController(), 'index');

    $router->matchcurrentrequest();
}
catch (\SwagFramework\Exceptions\SwagException $e)
{
    echo '<h1>SwagException !</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
}
catch (Exception $e)
{
    echo '<h1>Exception !</h1>';
    echo $e;
}
