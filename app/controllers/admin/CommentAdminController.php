<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 16/01/15
 * Time: 10:33
 */

namespace app\controllers\admin;

use SwagFramework\mvc\Controller;

class CommentAdminController extends Controller
{
    /**
     * @var \app\models\CommentsEventModel
     */
    private $model;

    public function index()
    {
        $this->model = $this->loadModel('CommentsEvent');
        $allEvents = $this->model->getAllCommentsEvent();
        $this->getView()->render('admin/event', array('allEvents' => $allEvents));
    }
}