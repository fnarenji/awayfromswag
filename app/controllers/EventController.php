<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:11
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

        foreach ($events as &$event) {
            $event = $this->getInfos($event);
        }

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
        $participate = $this->eventModel->getParticipateUser($id, Authentication::getInstance()->getUserId());
        $event['mine'] = $event['user']['id'] == Authentication::getInstance()->getUserId();

        $this->getView()->render('event/show', array(
            'event' => $event,
            'participate' => $participate
        ));
    }

    public function add()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $formHelper = new FormHelper();
        $form = $formHelper->generate('event', '/event/add');
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

    public function addPOST()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $formHelper = new FormHelper();
        $form = $formHelper->generate('event', '/event/add');

        $result = $form->validate([
            'name' => 'Nom de l\'évènement',
            'description' => 'Description',
            'address' => 'Adresse',
            'eventtime' => 'Date de l\'évènement',
            'money' => 'Prix',
            'personsmax' => 'Nombre maximum de participants'
        ]);

        var_dump($result);
        var_dump(Authentication::getInstance()->getUserId());

        $res = $this->eventModel->insertEvent(
            $result['name'],
            Authentication::getInstance()->getUserId(),
            $result['personsmax'],
            $result['description'],
            $result['address'],
            $result['eventtime'],
            $result['money']
        );

        var_dump($res);

//        $this->getView()->redirect('/event');
    }

    public function modify()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $id = (int)$this->getParams()[0];

        $event = $this->eventModel->get($id);

        if (empty($event)) {
            throw new EventNotFoundException($id);
        }

        if ($event['user'] != Authentication::getInstance()->getUserId()) {
            throw new NotYourEventException($id);
        }

        $form = new FormHelper();
        $form = $form->generate('event', '/event/modify');
        $form->setClass('pure-form pure-form-stacked');

        $form->getField('id')->addAttribute('value', $event['id']);
        $form->getField('name')->addAttribute('value', $event['name']);
        $form->getField('description')->setContent($event['description']);
        $form->getField('address')->setContent($event['address']);
        $form->getField('eventtime')->addAttribute('value', $event['eventtime']);
        $form->getField('money')->addAttribute('value', $event['money']);
        $form->getField('personsmax')->addAttribute('value', $event['personsmax']);

        $html = $form->getFormHTML([
            'name' => 'Nom de l\'évènement',
            'description' => 'Description',
            'address' => 'Adresse',
            'eventtime' => 'Date de l\'évènement',
            'money' => 'Prix',
            'personsmax' => 'Nombre maximum de participants'
        ]);

        $this->getView()->render('event/modify', ['form' => $html]);
    }

    public function modifyPOST()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $form = new FormHelper();
        $form = $form->generate('event', '/event/modify');
        $form->setClass('pure-form pure-form-stacked');

        $result = $form->validate([
            'id' => '',
            'name' => 'Nom de l\'évènement',
            'description' => 'Description',
            'address' => 'Adresse',
            'eventtime' => 'Date de l\'évènement',
            'money' => 'Prix',
            'personsmax' => 'Nombre maximum de participants'
        ]);

        $id = $result['id'];
        $event = $this->eventModel->get($id);

        if (empty($event)) {
            throw new EventNotFoundException($id);
        }

        if ($event['user'] != Authentication::getInstance()->getUserId()) {
            throw new NotYourEventException($id);
        }

        $this->eventModel->updateEvent(
            $id,
            $result['name'],
            $result['personsmax'],
            $result['description'],
            $result['address'],
            $result['eventtime'],
            $result['money']
        );

        $this->getView()->redirect('/event');
    }

    public function participate()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $id = (int)$this->getParams()[0];

        $event = $this->eventModel->get($id);

        if (empty($event)) {
            throw new EventNotFoundException($id);
        }

        $participate = $this->eventModel->getParticipateUser($id, Authentication::getInstance()->getUserId());

        if (empty($participate)) {
            throw new AlreadyParticipateEventException($id, Authentication::getInstance()->getUserId());
        }

        $this->eventModel->participate($id, Authentication::getInstance()->getUserId());

        $this->getView()->redirect('/event/show/' . $id);
    }

    public function unparticipate()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $id = (int)$this->getParams()[0];

        $event = $this->eventModel->get($id);

        if (empty($event)) {
            throw new EventNotFoundException($id);
        }

        $participate = $this->eventModel->getParticipateUser($id, Authentication::getInstance()->getUserId());

        if (!empty($participate)) {
            throw new NotParticipateEventException($id, Authentication::getInstance()->getUserId());
        }

        $this->eventModel->unparticipate($id, Authentication::getInstance()->getUserId());

        $this->getView()->redirect('/event/show/' . $id);
    }
}