<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 16/01/15
 * Time: 10:33
 */

namespace app\controlers\admin;


class CommentAdminControler
{

    /**
     * @var \app\models\CommentsModel
     */
    private $model;

    // NOT WORKING //
    public function index()
    {
        $this->model = $this->loadModel('Comments');
        $allEvents = $this->model->getAllCommentsEvent();
        $this->getView()->render('admin/event', array('allEvents' => $allEvents));
    }

}