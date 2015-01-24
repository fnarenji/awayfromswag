<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:31
 */

namespace app\controllers;


use app\exceptions\AlreadyParticipateEventException;
use app\exceptions\EventNotFoundException;
use app\exceptions\NotAuthenticatedException;
use app\exceptions\NotParticipateEventException;
use app\exceptions\NotYourEventException;
use app\models\EventModel;
use app\models\UserModel;
use SwagFramework\Helpers\Authentication;
use SwagFramework\Helpers\FormHelper;
use SwagFramework\mvc\Controller;

class AdminEventController extends Controller
{
    /**
     * @var \app\models\EventModel
     */
    private $eventModel;

    public function index()
    {
        $this->eventModel = $this->loadModel('Event');
        $allEvents = $this->eventModel->getAll();

        foreach($allEvents as $key => $value)
        {
            $allEvents[$key]['description'] = substr($allEvents[$key]['description'], 0, 30);
        }

        $this->getView()->render('admin/events', array('allEvents' => $allEvents));
    }
    
    public function delete()
    {
        $this->eventModel = $this->loadModel('Event');
        $event = (int)$this->getParams()[0];
        $this->eventModel->deleteEventId($event);
        $this->index();
    }


}