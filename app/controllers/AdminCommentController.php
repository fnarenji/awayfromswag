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

    public function view()
    {
        $id = (int)$this->getParams()[0];
        $this->modelComment = $this->loadModel('CommentsEvent');

        $comment = $this->modelComment->getCommentEvent($id);
        print_r($comment)[0];
        //$this->getView()->render('admin/comment', array('comments' => $comment));
    }

    public function delete()
    {
        {
            $id = (int)$this->getParams()[0];
            $this->modelComment = $this->loadModel('CommentsEvent');

            $this->modelComment->deleteCommentEvent($id);
    }
}