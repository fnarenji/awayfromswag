<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 01/01/15
 * Time: 20:27
 */

namespace app\controllers;

use app\models\EventModel;
use SwagFramework\mvc\Controller;

class HomeController extends Controller
{
    /**
     * @var EventModel
     */
    private $modelEvent;

    public function index()
    {
        // EVENT
        $this->modelEvent = $this->loadModel('Event');
        $events['top'] = $this->modelEvent->getTop();
        $events['last'] = $this->modelEvent->getLast();

        $this->getView()->render('home/index', ['events' => $events]);
    }
}