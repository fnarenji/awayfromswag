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
        $this->getView()->render('admin/events', array('allEvents' => $allEvents));
    }

    public function event()
    {
        $this->eventModel = $this->loadModel('Event');

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

        $this->getView()->render('admin/event', ['form' => $html,'id' => $event['id']]);
    }
    public function delete()
    {
        $this->eventModel = $this->loadModel('Event');
        $event = (int)$this->getParams()[0];
        $this->eventModel->deleteEventId($event);
        $this->index();
    }


}