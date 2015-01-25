<?php

require 'vendor/autoload.php';
use app\helpers\ClassRouting;
use app\models\ConversationModel;
use app\models\EventModel;
use app\models\LittleModel;
use app\models\UserModel;
use SwagFramework\Database\DatabaseProvider;
use SwagFramework\Helpers\Authentication;
use SwagFramework\Helpers\BaseViewContextProvider;

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
    DatabaseProvider::connect("app/config/database.json");
    BaseViewContextProvider::setProvider(function () {
        return [
            'count' => [
                'event' => (new EventModel())->count()['nb'],
                'user' => (new UserModel())->count()['nb'],
                'online' => (new LittleModel())->checkConnect()['COUNT(*)']
            ],
            'newmessages' => Authentication::getInstance()->isAuthenticated()
                ? (new ConversationModel())->countUnreadMessages()
                : 0,
        ];
    });

    $router = new \SwagFramework\Routing\Router();

    $classRouting = new ClassRouting('\app\controllers\\');
    $classRouting->addClass('User');
    $classRouting->addClass('Event');
    $classRouting->addClass('Article');
    $classRouting->addClass('Parteners');
    $classRouting->addClass('Search');

    if(Authentication::getInstance()->isAuthenticated())
    {
        $classRouting->addClass('Conversation');

        if (Authentication::getInstance()->getOptionOr('accessLevel', 0))
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
