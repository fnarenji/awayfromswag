<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 01/01/15
 * Time: 20:27
 */

namespace app\controllers;

use app\models\EventModel;
use app\models\LittleModel;
use SwagFramework\mvc\Controller;

class HomeController extends Controller
{
    /**
     * @var EventModel
     */
    private $modelEvent;

    /**
     * @var LittleModel
     */
    private $modelConnect;

    public function index()
    {
        // EVENT
        $this->modelEvent = $this->loadModel('Event');
        $this->modelConnect = $this->loadModel('Little');
        $tmp =$this->modelConnect->checkConnect();
        $events['top'] = $this->modelEvent->getTop();
        $events['last'] = $this->modelEvent->getLast();
        $events['nbConnect'] = (int)$tmp['COUNT(*)'];

        $this->getView()->render('home/index', ['events' => $events]);
    }
}