<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 24/01/15
 * Time: 16:59
 */

namespace tests\Models;
use app\models\CommentsArticleModel;
use app\models\CommentsEventModel;


class CommentsModelTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \app\models\CommentsEventModel
     */
    private $modelEvent;

    /**
     * @var \app\models\CommentsArticleModel
     */
    private $modelArticle;

    public function testGetAllEvent()
    {
        $this->modelEvent = new CommentsEventModel();
        $state = $this->modelEvent->getAllCommentsEvent();
        $this->assertNotEmpty($state);
    }

    public function testAllEvent()
    {
        $this->modelEvent = new CommentsEventModel();

        $array = ['idevent'=>2,'iduser'=>1,'contents'=>'Trop SWAG'];
        $state = $this->modelEvent->insertCommentEvent($array);
        $this->assertEquals(true,$state);

        $array = $this->modelEvent->getCommentEvent(2);
        $this->assertNotEmpty($array);

        $state = $this->modelEvent->deleteCommentEvent(0);
        $this->assertEquals(true,$state);
    }


    public function testGetAllArticle()
    {
        $this->modelArticle = new CommentsArticleModel();
        $state = $this->modelArticle->getAllCommentsArticle();
        $this->assertEmpty($state);
    }

    public function testAllArticle()
    {
        $this->modelArticle = new CommentsArticleModel();

        $array = ['idarticle'=>46,'iduser'=>1,'contents'=>'Trop SWAG'];
        $state = $this->modelArticle->insertCommentArticle($array);
        $this->assertEquals(true,$state);

        $state = $this->modelArticle->getCommentArticle(46);
        $this->assertNotEmpty($state);

        $state = $this->modelArticle->deleteCommentArticle(0);
        $this->assertEquals(true,$state);
    }




}