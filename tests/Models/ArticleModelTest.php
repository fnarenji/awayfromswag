<?php
/**
 * Created by PhpStorm.
 * User: loicpauletto
 * Date: 24/01/15
 * Time: 15:44
 */

namespace tests\Models;
use app\models\NewsModel;



class ArticleModelTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \app\models\NewsModel
     */
    private $model;

    public function testGetAll()
    {
        $this->model = new NewsModel();
        $state = $this->model->getNews();
        $this->assertEmpty($state);
    }

    public function testAll()
    {
        $this->model = new NewsModel();

        $array = ['user'=>'1','title'=>'plop','text'=>'test','postdate'=>date('Y-m-d G:i:s'),'category'=>'1'];
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