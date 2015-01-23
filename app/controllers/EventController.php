<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:11
 */

namespace app\controllers;


use app\exceptions\EventNotFoundException;
use app\exceptions\NotAuthenticatedException;
use app\models\EventModel;
use app\models\UserModel;
use SwagFramework\Helpers\Authentication;
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

        foreach($events as &$event)
            $event = $this->getInfos($event);

        $this->getView()->render('event/index', array(
            'events' => $events
        ));
    }

    private function getInfos($event)
    {
        $event['user'] = $this->userModel->getUser($event['user']);

        $createtime = new \DateTime($event['createtime']);
        $event['createtime'] = $createtime->format('d/m/Y à H:i');

        $eventtime = new \DateTime($event['eventtime']);
        $event['eventtime'] = $eventtime->format('d/m/Y à H:i');

        return $event;
    }

    public function show()
    {
        $id = (int)$this->getParams()[0];

        $event = $this->eventModel->get($id);

        if (empty($event)) {
            throw new EventNotFoundException($id);
        }

        $event = $this->getInfos($event);

        $this->getView()->render('event/show', array(
            'event' => $event
        ));
    }

    public function performPOST()
    {
        if(!Authentication::getInstance()->isAuthenticated())
            throw new NotAuthenticatedException();

        $formHelper = new Form();
        $form = $formHelper->generate('event', '/event/perform');

        $result = $form->validate(array(
            'name' => 'Nom de l\'évènement',
            'description' => 'Description',
            'address' => 'Adresse',
            'eventtime' => 'Date de l\'évènement',
            'money' => 'Prix',
            'personsmax' => 'Nombre maximum de participants'
        ));

        $this->eventModel->insertEvent(
            $result['name'],
            Authentication::getInstance()->getUserId(),
            $result['personsmax'],
            $result['description'],
            $result['address'],
            $result['eventtime'],
            $result['money']
        );

        $this->getView()->redirect('/event');
    }

    public function add()
    {
        if(!Authentication::getInstance()->isAuthenticated())
            throw new NotAuthenticatedException();

        $formHelper = new Form();
        $form = $formHelper->generate('event', '/event/perform');
        $form->setClass('pure-form pure-form-stacked');

        $html = $form->getFormHTML([
            'name' => 'Nom de l\'évènement',
            'description' => 'Description',
            'address' => 'Adresse',
            'eventtime' => 'Date de l\'évènement',
            'money' => 'Prix',
            'personsmax' => 'Nombre maximum de participants'
        ]);

        $this->getView()->render('event/add', ['form' => $html]);
    }
}