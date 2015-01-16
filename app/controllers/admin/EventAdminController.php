<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:31
 */

namespace app\controllers\admin;


use SwagFramework\mvc\Controller;

class EventAdminController extends Controller
{
    /**
     * @var \app\models\EventModel
     */
    private $model;

    public function index()
    {
        $this->model = $this->loadModel('Event');
        $allEvents = $this->model->getEvents();
        $this->getView()->render('admin/event', array('allEvents' => $allEvents));
    }

    public function delete()
    {
        $event = (int)$this->getParams()[0];
        $this->model->deleteEvent($event);
    }

    public function update()
    {
        $event = (int)$this->getParams()[0];
        //TO DO, NO DB
        $this->model->updateEvent($event);
    }


}