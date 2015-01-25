<?php
/**
 * Created by PhpStorm.
 * User: loicpauletto
 * Date: 24/01/15
 * Time: 15:44
 */

namespace tests\Models;

use app\models\ArticleModel;


class ArticleModelTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \app\models\ArticleModel
     */
    private $model;

    public function testGetAll()
    {
        $this->model = new ArticleModel();
        $state = $this->model->getNews();
        $this->assertNotEmpty($state);
    }

    public function testAll()
    {
        $this->model = new ArticleModel();

        $array = ['user' => '1', 'title' => 'plop', 'text' => 'test', 'image' => 'http://lol.com/lol.png', 'postdate' => date('Y-m-d G:i:s'), 'category' => '1'];
        $state = $this->model->insertNews($array);
        $this->assertEquals(true,$state);

        $state = $this->model->getOneNewsByName('plop');
        $this->assertNotEmpty($state);

        $state = $this->model->updateNewsByName('plop',date('Y-m-d G:i:s'),'Je sais toujours pas');
        $this->assertEquals(true,$state);

        $state = $this->model->deleteNewsByName('plop');
        $this->assertEquals(true,$state);
    }

}