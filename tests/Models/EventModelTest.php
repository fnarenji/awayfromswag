<?php
/**
 * Created by PhpStorm.
 * User: loicpauletto
 * Date: 24/01/15
 * Time: 14:25
 */

namespace tests\Models;
use app\models\EventModel;

class EventModelTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \app\models\EventModel
     */
    private $model;

    public function testInsertDelete()
    {
        $this->model = new EventModel();
        $array = ['name'=>'WUT','user'=>'1','description'=>'No Se', 'address'=>'pffff', 'eventtime'=>'1/1/2000', 'money'=>'1', 'personsmax'=>'1'];

        $state = $this->model->insertEvent($array);
        $this->assertEquals(true,$state);

        $state = $this->model->deleteEventName('WUT');
        $this->assertEquals(true,$state);
    }

    public function testGetAll()
    {
        $this->model = new EventModel();
        $state = $this->model->getAll();
        $this->assertNotEmpty($state);
    }

    public function testGetOne()
    {
        $this->model = new EventModel();
        $state = $this->model->get(1);
        $this->assertNotEmpty($state);
    }

    public function testUpdate()
    {
        $this->model = new EventModel();
        $array = ['name'=>'WUT','user'=>'1','description'=>'No Se', 'address'=>'pffff', 'eventtime'=>'1/1/2000', 'money'=>'1', 'personsmax'=>'1'];

        $state = $this->model->insertEvent($array);
        $this->assertEquals(true,$state);

        $array = ['name'=>'WUT','description'=>'No Se por que'];
        $state = $this->model->updateEventByName($array);
        $this->assertEquals(true,$state);

        $state = $this->model->deleteEventName('WUT');
        $this->assertEquals(true,$state);
    }
}