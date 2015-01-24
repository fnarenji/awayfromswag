<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 16/01/15
 * Time: 10:33
 */

namespace app\controllers;

use SwagFramework\mvc\Controller;

class AdminCommentController extends Controller
{
    /**
     * @var \app\models\CommentsEventModel
     */
    private $modelComment;

    public function index()
    {
        $this->modelComment = $this->loadModel('CommentsEvent');
        $allComments = $this->modelComment->getAllCommentsEvent();
        $this->getView()->render('admin/comments', array('allComments' => $allComments));
    }
}