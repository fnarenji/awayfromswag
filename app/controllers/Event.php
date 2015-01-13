<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:11
 */

namespace app\controllers;


use app\models\EventModel;
use SwagFramework\Exceptions\EventNotFoundException;
use SwagFramework\mvc\Controller;

class Event extends Controller
{

    /**
     * @var EventModel
     */
    private $model;

    function __construct()
    {
        $this->model = new EventModel();
    }

    public function index()
    {
        $events = $this->model->getAll();
        var_dump($events);

//        $this->getView()->render('event/index', array(
//            'events' => $events
//        ));
    }

    public function show()
    {
        $id = (int)$this->getParams()[0];

        $event = $this->model->get($id);

        $error = array();
        if (empty($event)) {
            throw new EventNotFoundException($id);
        }

//        $this->getView()->render('event/show', array(
//            'event' => $event,
//            'error' => $error
//        ));
    }

    public function add()
    {
        //TODO: add new event from user
    }
}