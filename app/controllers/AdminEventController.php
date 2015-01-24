<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:31
 */

namespace app\controllers;


use SwagFramework\mvc\Controller;

class AdminEventController extends Controller
{
    /**
     * @var \app\models\EventModel
     */
    private $eventModel;

    public function delete()
    {
        $this->eventModel = $this->loadModel('Event');
        $event = (int)$this->getParams()[0];
        $this->eventModel->deleteEventId($event);
        $this->index();
    }

    public function index()
    {
        $this->eventModel = $this->loadModel('Event');
        $allEvents = $this->eventModel->getAll();

        foreach ($allEvents as $key => $value) {
            $allEvents[$key]['description'] = substr($allEvents[$key]['description'], 0, 30);
        }

        $this->getView()->render('admin/events', array('allEvents' => $allEvents));
    }


}