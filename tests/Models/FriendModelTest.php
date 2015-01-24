<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 24/01/15
 * Time: 20:35
 */

namespace tests\Models;
use app\models\FriendModel;


class FriendModelTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \app\models\FriendModel
     */
    private $model;

    public function testGet()
    {
        $this->model = new FriendModel();
        $array = $this->model->getAllFriendById(1);
        $this->assertNotEmpty($array);
    }

    public function testAll()
    {
        $this->model = new FriendModel();
    }

}