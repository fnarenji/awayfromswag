<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:11
 */

namespace app\controlers;


use app\models\EventModel;
use SwagFramework\mvc\Controler;

class EventControler extends Controler
{

    /**
     * @var EventModel
     */
    private $model;

    function __construct()
    {
        parent::__construct();
        $this->model = $this->loadModel('Event');
    }

    public function index()
    {
        $events = $this->model->getEvents();

        $this->getView()->render('event/index', array(
            'events' => $events
        ));
    }

    public function show()
    {
//        $id = (int)$this->getParams()[0];

//        $event = $this->model->getOneEvents($id);

//        $error = array();
//        if (empty($event))
//            throw new EventNotFoundException($id);

        $this->getView()->render('event/show');
    }

    public function add()
    {
        $formHelper = new \SwagFramework\Helpers\Form();
        $form = $formHelper->generate('events', '#');

        $this->getView()->render('event/add', array(
            'form' => $form
        ));
    }
}