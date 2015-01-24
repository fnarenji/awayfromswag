<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 24/01/15
 * Time: 16:33
 */

namespace tests\Models;
use app\models\ConversationModel;

class ConversationModelTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \app\models\ConversationModel
     */
    private $model;

    public function testGetAll()
    {
        $this->model = new ConversationModel();
        $state = $this->model->getAll();
        $this->assertNotEmpty($state);
    }

}