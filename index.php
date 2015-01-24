<?php

require 'vendor/autoload.php';
use app\helpers\ClassRouting;
use SwagFramework\Helpers\Authentication;

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

function main()
{
    \SwagFramework\Database\DatabaseProvider::connect(\SwagFramework\Config\DatabaseConfig::parseFromFile("app/config/database.json"));

    $router = new \SwagFramework\Routing\Router();

    $classRouting = new ClassRouting('\app\controllers\\');
    $classRouting->addClass('User');
    $classRouting->addClass('Event');

    if(Authentication::getInstance()->isAuthenticated())
    {
        $classRouting->addClass('Conversation');

        if (Authentication::getInstance()->getOptionOr('accessLevel', 0) == 1)
        {
            $classRouting->addClass('AdminUsers');
            $classRouting->addClass('AdminEvent');
            $classRouting->addClass('AdminComment');
            $classRouting->addClass('Admin');
        }
    }

    $classRouting->generateRoute($router);

    $router->add('/errors/err404', new \app\controllers\ErrorsController(), 'err404');
    $router->add('/', new \app\controllers\HomeController(), 'index');

    $router->matchCurrentRequest();
}

if (DEBUG) {
    main();
} else {
    try {
        main();
    } catch (Exception $e) {
        echo 'Internal server error.';
    }
}
