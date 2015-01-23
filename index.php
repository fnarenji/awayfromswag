<?php

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

    $classrouting = new \app\helpers\ClassRouting('\app\controlers\\');
    $classrouting->addclass('User');
    $classrouting->generateroute($router);

    $router->add('/user/auth', new \app\controlers\UserControler(), 'auth', 'POST');
    $router->add('/admin/index', new \app\controlers\admin\EventAdminControler(), 'index');
    $router->add('/errors/err404', new \app\controlers\ErrorsControler(), 'err404');
    $router->add('/admin', new \app\controlers\admin\EventAdminControler(), 'index');
    $router->add('/', new \app\controlers\HomeControler(), 'index');


    $router->matchcurrentrequest();

} catch (\SwagFramework\Exceptions\SwagException $e) {
    echo '<h1>SwagException !</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
} catch (Exception $e) {
    echo '<h1>Exception !</h1>';
    echo $e;
}
