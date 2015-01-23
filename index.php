<?php

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
require 'vendor/autoload.php';

try {
    \SwagFramework\Database\DatabaseProvider::connect(\SwagFramework\Config\DatabaseConfig::parseFromFile("app/config/database.json"));

    $router = new \SwagFramework\Routing\Router();

    $classRouting = new \app\helpers\ClassRouting('\app\controllers\\');
    $classRouting->addClass('User');
    $classRouting->addClass('Conversation');
    $classRouting->addClass('Event');
    $classRouting->generateRoute($router);

    $router->add('/admin/index', new \app\controllers\admin\EventAdminController(), 'index');
    $router->add('/errors/err404', new \app\controllers\ErrorsController(), 'err404');
    $router->add('/admin', new \app\controllers\admin\EventAdminController(), 'index');
    $router->add('/', new \app\controllers\HomeController(), 'index');

    $router->matchCurrentRequest();

} catch (SwagException $e) {
    echo '<h1>SwagException !</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
} catch (Exception $e) {
    echo '<h1>Exception !</h1>';
    echo $e;
}
