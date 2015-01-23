<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 18:50
 */

namespace tests\Form;


use SwagFramework\Form\Field\InputField;

class InputTest extends \PHPUnit_Framework_TestCase
{

    private $input;

    public function testInput()
    {
        $this->InputEmpty();
        $this->InputTest();
    }

    private function InputEmpty()
    {
        $this->input = new InputField('testEmpty');
        $this->assertEquals('<input name="testEmpty" />', $this->input->getHTML());
    }

    private function InputTest()
    {
        $this->input = new InputField('testInput');
        $this->input->addAttribute('test', 'testValue');
        $this->assertEquals('<input name="testInput" test="testValue" />', $this->input->getHTML());
    }

}