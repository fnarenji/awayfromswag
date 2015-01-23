<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:11
 */

namespace app\controllers;


use app\models\EventModel;

class EventController extends Controller
{

    /**
     * @var EventModel
     */
    private $model;

    function __construct()
    {
        $this->model = new EventModel();
        parent::__construct();
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

    public function performAdd()
    {

    }

    public function add()
    {
        $formHelper = new Form();
        $form = $formHelper->generate('event', '/event/performAdd');

        $form->setAction('/event/perfomAdd');

        $html = $form->getFormHTML(array(
            'name' => 'Nom de l\'évènement',
            'description' => 'Description',
            'adress' => 'Adresse',
            'eventtime' => 'Date de l\'évènement',
            'money' => 'Prix',
            'personsmax' => 'Nombre maximum de participants'
        ));

        $this->getView()->render('event/add', array(
            'form' => $html
        ));
    }
}