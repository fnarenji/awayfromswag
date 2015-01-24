<?php

namespace tests\Models;
use SwagFramework\mvc\Controller;
use app\models\UserModel;

/**
 * Created by PhpStorm.
 * User: loic
 * Date: 24/01/15
 * Time: 08:47
 */

class UserModelTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \app\models\UserModel
     */
    private $model;

    public function testGetAll()
    {
        $this->model = new UserModel();
        $array = $this->model->getAllUsers();
        $this->assertNotNull($array);
    }

    public function testGet()
    {
        $this->model = new UserModel();
        $array = $this->model->getUser(1);
        $this->assertNotNull($array);
    }

    public function insertDelete()
    {
        $this->model = new UserModel();

        $array =    ['username'=>'lol','firstname'=>'swag','lastname'=>'OMG','mail'=>'op@swag.fr','birthday'=>'04/12/1900',
                    'phonenumber'=>'000000','twitter'=>'Nope','skype'=>'nope','facebookuri'=>'OSEF','website'=>'??','job'=>'Pole emploi','description'=>'???',
                    'privacy'=>123,'mailnotifications'=>1,'accesslevel'=>1];

        $ok = $this->model->insertUser($array);
        $this->assertEquals(true,$ok);

        //$ok = $this->model->deleteUser(2);
        //$this->assertEquals(true,$ok);
    }

    public function updateUser()
    {
        $this->model = new UserModel();

        $array =    ['id'=>1,'firstname'=>'OMG'];
        $ok = $this->model->updateUser($array);
        $this->assertEquals(true,$ok);
    }

}