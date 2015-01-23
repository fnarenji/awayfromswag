<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:11
 */

namespace app\controllers;


use app\models\EventModel;
use SwagFramework\Config\DatabaseConfig;
use SwagFramework\Database\Database;
use SwagFramework\Exceptions\EventNotFoundException;
use SwagFramework\mvc\Controller;

class EventController extends Controller
{

    /**
     * @var EventModel
     */
    private $model;

    function __construct()
    {
        parent::__construct();
        $this->model = new EventModel(new Database(DatabaseConfig::parseFromFile()));
    }

    public function index()
    {
        $events = $this->model->getEvents();
        var_dump($events);

        $this->getView()->render('event/index', array(
            'events' => $events
        ));
    }

    public function show()
    {
        $id = (int)$this->getParams()[0];

        $event = $this->model->getOneEvents($id);

        $error = array();
        if (empty($event)) {
            throw new EventNotFoundException($id);
        }

        $this->getView()->render('event/show', array(
            'event' => $event,
            'error' => $error
        ));
    }

    public function add()
    {
        //TODO: add new event from user
    }
}