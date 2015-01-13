<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 18:47
 */

namespace tests\Form;


use SwagFramework\Form\Field\Input;
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
        $this->form->addField(new Input('test1'));
        $this->assertEquals('<form method="POST" action="test">
    <input name="test1" />
</form>', $this->form->getFormHTML());
    }

    private function createWithLabel()
    {
        $this->form = new Form();
        $this->form->setAction('test');
        $this->form->addField(new Input('test1'));
        $this->assertEquals('<form method="POST" action="test">
    <label for="test1">Test1</label>
    <input name="test1" />
</form>', $this->form->getFormHTML(array('test1' => 'Test1')));
    }

    public function testForm()
    {
        $this->create();
        $this->createWithLabel();
    }
}