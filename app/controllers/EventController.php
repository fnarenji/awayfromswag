<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:11
 */

namespace app\controllers;


use app\exceptions\AlreadyParticipateEventException;
use app\exceptions\EventFullException;
use app\exceptions\EventNotFoundException;
use app\exceptions\NotAuthenticatedException;
use app\exceptions\NotParticipateEventException;
use app\exceptions\NotYourEventException;
use app\models\CommentsEventModel;
use app\models\EventModel;
use app\models\ParticipateModel;
use app\models\UserModel;
use SwagFramework\Form\Field\InputField;
use SwagFramework\Form\Form;
use SwagFramework\Helpers\Authentication;
use SwagFramework\Helpers\FormHelper;
use SwagFramework\Helpers\Input;
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

    /**
     * @var ParticipateModel
     */
    private $participateModel;

    /**
     * @var CommentsEventModel
     */
    private $eventCommentModel;

    function __construct()
    {
        $this->eventModel = $this->loadModel('Event');
        $this->userModel = $this->loadModel('User');
        $this->participateModel = $this->loadModel('Participate');
        $this->eventCommentModel = $this->loadModel('CommentsEvent');
        parent::__construct();
    }

    public function index()
    {
        $page = 0;
        if (!empty($this->getParams(true))) {
            $page = (int)$this->getParams()[0] - 1;
        }

        $total = $this->eventModel->count()['nb'];
        $total = (int)ceil($total / 10);

        $events = $this->eventModel->getAll($page * 10, 10);

        foreach ($events as &$event) {
            $event = $this->getInfos($event);
        }

        $this->getView()->render('event/index',
            ['events' => $events, 'page' => ['actual' => $page + 1, 'total' => $total]]);
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
        $participate = (Authentication::getInstance()->isAuthenticated()) ? $this->eventModel->getParticipateUser($id,
            Authentication::getInstance()->getUserId()) : null;
        $event['mine'] = (Authentication::getInstance()->isAuthenticated()) ? $event['user']['id'] == Authentication::getInstance()->getUserId() : false;

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

        $form = FormHelper::generate('event', '/event/add');

        $form->getField('eventtime')->addAttribute('class', 'datepicker');

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

        if (!isset($_POST['money']))
            $_POST['money'] = 0;

        $form = FormHelper::generate('event', '/event/add');
        $form->setClass('pure-form pure-form-aligned centered');

        $result = $form->validate([
            'name' => 'Nom de l\'évènement',
            'description' => 'Description',
            'address' => 'Adresse',
            'eventtime' => 'Date de l\'évènement',
            'money' => 'Prix',
            'personsmax' => 'Nombre maximum de participants'
        ]);

        $result['user'] = Authentication::getInstance()->getUserId();

        $eventtime = new \DateTime();
        $eventtime = $eventtime->createFromFormat('d/m/Y', $result['eventtime']);
        $result['eventtime'] = $eventtime->format('Y-m-d H:i:s');

        $this->eventModel->insertEvent($result);

        $this->getView()->redirect('/event');
    }

    public function modify()
    {
        if (!Authentication::getInstance()->isAuthenticated() && !Authentication::getInstance()->getAccessLevel()) {
            throw new NotAuthenticatedException();
        }

        $id = (int)$this->getParams()[0];

        $event = $this->eventModel->get($id);

        if (empty($event)) {
            throw new EventNotFoundException($id);
        }

        if ($event['user'] != Authentication::getInstance()->getUserId() && !Authentication::getInstance()->getOptionOr('accessLevel', 0)) {
            throw new NotYourEventException($id);
        }

        $form = FormHelper::generate('event', '/event/modify');

        $form->getField('id')->addAttribute('value', $event['id']);
        $form->getField('name')->addAttribute('value', $event['name']);
        $form->getField('description')->setContent($event['description']);
        $form->getField('address')->setContent($event['address']);

        $eventtime = new \DateTime();
        $eventtime = $eventtime->createFromFormat('Y-m-d H:i:s', $event['eventtime']);

        $form->getField('eventtime')->addAttribute('value', $eventtime->format('d/m/Y'));
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

        $formDelete = new Form('/event/delete');
        $formDelete->setClass('pure-form centered');
        $formDelete->addField(new InputField('id', ['type' => 'hidden', 'value' => $id]));
        $formDelete->addField(new InputField('submit',
            ['type' => 'submit', 'value' => 'Supprimer', 'class' => 'pure-button button-error']));

        $this->getView()->render('event/modify', ['form' => $html, 'formDelete' => $formDelete->getFormHTML([])]);
    }

    public function modifyPOST()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $form = FormHelper::generate('event', '/event/modify');
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

        $eventtime = new \DateTime();
        $eventtime = $eventtime->createFromFormat('d/m/Y', $result['eventtime']);
        $result['eventtime'] = $eventtime->format('Y-m-d H:i:s');

        $this->eventModel->updateEventById($result);

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

        if ($event['personsnow'] == 0) {
            throw new EventFullException($id);
        }

        $participate = $this->eventModel->getParticipateUser($id, Authentication::getInstance()->getUserId());

        if (!empty($participate)) {
            throw new AlreadyParticipateEventException($id, Authentication::getInstance()->getUserId());
        }

        $this->participateModel->insertEventParticipation($id, Authentication::getInstance()->getUserId());

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

        if (empty($participate)) {
            throw new NotParticipateEventException($id, Authentication::getInstance()->getUserId());
        }

        $this->participateModel->deleteEventParticipation($id, Authentication::getInstance()->getUserId());

        $this->getView()->redirect('/event/show/' . $id);
    }

    public function delete()
    {
        $this->getView()->redirect('/event/');
    }

    public function deletePOST(){
        $input = new Input();
        $eventId = $input->post('id');

        $creatorId = $this->eventModel->getCreatorId($eventId);

        if(Authentication::getInstance()->getUserId() == $creatorId || Authentication::getInstance()->getOptionOr('accessLevel', 0) == 1)
        {
            $this->eventModel->deleteEventId($eventId);
        }

        $this->getView()->redirect('/');
    }

    public function comment()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $id = (int)$this->getParams()[0];
        $this->getView()->redirect('/event/show/' . $id);
    }

    public function commentPOST()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $id = (int)$this->getParams()[0];

        $this->eventCommentModel->insertCommentArticle(Authentication::getInstance()->getUserId(), $id, Input::post('message'));

        $this->getView()->redirect('/event/show/' . $id);
    }
}