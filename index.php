<?php

require 'vendor/autoload.php';
use app\helpers\ClassRouting;
use SwagFramework\Exceptions\SwagException;

session_start();

define('CR', "\n");
define('TAB', '    ');

define('DS', DIRECTORY_SEPARATOR);
define('FSROOT', __DIR__ . DS);

if (dirname($_SERVER['SCRIPT_NAME']) != '/') {
    define('WEBROOT', dirname($_SERVER['SCRIPT_NAME']) . DS);
} else {
    define('WEBROOT', dirname($_SERVER['SCRIPT_NAME']));
}
define('DEBUG', true);

if (DEBUG) {
    ini_set('display_errors', true);
    ini_set('html_errors', true);
    error_reporting(E_ALL);
}

try {
    \SwagFramework\Database\DatabaseProvider::connect(\SwagFramework\Config\DatabaseConfig::parseFromFile("app/config/database.json"));

    $router = new \SwagFramework\Routing\Router();

    $classRouting = new ClassRouting('\app\controllers\\');
    $classRouting->addClass('User');
    $classRouting->addClass('Conversation');
    $classRouting->addClass('Event');
    $classRouting->addClass('AdminUsers');
    $classRouting->addClass('AdminEvent');
    $classRouting->addClass('AdminComment');
    $classRouting->addClass('Admin');
    $classRouting->generateRoute($router);

    $router->add('/errors/err404', new \app\controllers\ErrorsController(), 'err404');
    $router->add('/', new \app\controllers\HomeController(), 'index');

    $router->matchCurrentRequest();
} catch (SwagException $e) {
    if (DEBUG) {
        echo '<h1>SwagException !</h1>';
        echo '<p>' . $e->getMessage() . '</p>';
    } else echo 'Internal server error.';
} catch (Exception $e) {
    if (DEBUG) {
        echo '<h1>Exception !</h1>';
        echo $e;
    } else echo 'Internal server error.';
}
