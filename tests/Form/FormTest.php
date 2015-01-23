<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 18:47
 */

namespace tests\Form;


use SwagFramework\Form\Field\InputField;
use SwagFramework\Form\Form;

define('CR', "\n");
define('TAB', "    ");

class FormTest extends \PHPUnit_Framework_TestCase
{

    private $form;

    private function create()
    {
        $this->form = new Form();
        $this->form->setAction('test');
        $this->form->addField(new InputField('test1'));
        $this->assertEquals('<form method="POST" action="test" id="" class="">
    <label for="test1"></label>
    <input name="test1" />
</form>', $this->form->getFormHTML(array(
            'test1' => ''
        )));
    }

    private function createWithLabel()
    {
        $this->form = new Form();
        $this->form->setAction('test');
        $this->form->addField(new InputField('test1'));
        $this->assertEquals('<form method="POST" action="test" id="" class="">
    <label for="test1">Test1</label>
    <input name="test1" />
</form>', $this->form->getFormHTML(array(
            'test1' => 'Test1'
        )));
    }

    public function testForm()
    {
        $this->create();
        $this->createWithLabel();
    }
}