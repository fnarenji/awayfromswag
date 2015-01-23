<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:11
 */

namespace app\controllers;


use app\exceptions\EventNotFoundException;
use app\models\EventModel;
use app\models\UserModel;
use SwagFramework\Helpers\Form;
use SwagFramework\mvc\Controller;

class EventController extends Controller
{

    /**
     * @var EventModel
     */
    private $eventModel;

    /**
     * @var UserModel
     */
    private $userModel;

    function __construct()
    {
        $this->eventModel = new EventModel();
        $this->userModel = new UserModel();
        parent::__construct();
    }

    public function index()
    {
        $events = $this->eventModel->getAll();

        $this->getView()->render('event/index', array(
            'events' => $events
        ));
    }

    public function show()
    {
        $id = (int)$this->getParams()[0];

        $event = $this->eventModel->get(1);

        if (empty($event)) {
            throw new EventNotFoundException($id);
        }

        $event['user'] = $this->userModel->getUser($event['user']);

        $this->getView()->render('event/show', array(
            'event' => $event
        ));
    }

    public function performAdd()
    {

    }

    public function add()
    {
        $formHelper = new Form('/event/perfomAdd');
        $form = $formHelper->generate('event', '/event/performAdd');

        $form->setAction('/event/perfomAdd');
        $form->setClass('pure-form pure-form-stacked');

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