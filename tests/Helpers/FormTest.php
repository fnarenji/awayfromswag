<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 23/01/15
 * Time: 15:30
 */

namespace tests\Helpers;


use SwagFramework\Helpers\Form;

class FormTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Form
     */
    private $helper;

    function __construct()
    {
        $this->helper = new Form();
    }

    private function userForm() {
        $form = $this->helper->generate('user', '#');
        var_dump($form);
    }

    public function testForm()
    {
        $this->userForm();
    }
}