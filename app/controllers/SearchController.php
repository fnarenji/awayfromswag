<?php
/**
 * Created by PhpStorm.
 * User: sknz
 * Date: la veille du rendu, comme d'hab
 * Time: 21:35
 */

namespace app\controllers;

use SwagFramework\Helpers\Input;
use SwagFramework\mvc\Controller;

class SearchController extends Controller
{
    private $userModel;
    private $articleModel;
    private $eventModel;

    public function __construct()
    {
        $this->userModel = $this->loadModel('User');
        $this->articleModel = $this->loadModel('Article');
        $this->eventModel = $this->loadModel('Event');
        parent::__construct();
    }

    public function index()
    {
        $query = Input::get('query', true);
        $results = [];
        if (!empty($query)) {
            $results['users'] = $this->userModel->search($query);
            $results['articles'] = $this->articleModel->search($query);
            $results['events'] = $this->eventModel->search($query);
        }
        $this->getView()->render('search/index', $results);
    }
}