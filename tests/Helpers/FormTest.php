<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 23/01/15
 * Time: 15:30
 */

namespace tests\Helpers;


use SwagFramework\Helpers\Form;

class FormTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Form
     */
    private $helper;

    function __construct()
    {
        $this->helper = new Form();
    }

    public function testForm()
    {
        $this->userForm();
    }

    private function userForm()
    {
        $result = '<form method="POST" action="" id="" class="">
    <input name="id" type="hidden" />
    <label for="username">Nom d\'utilisateur</label>
    <input name="username" type="text" />
    <label for="firstname">Prénom</label>
    <input name="firstname" type="text" />
    <input name="submit" type="submit" value="Envoyer" />
</form>';

        $form = $this->helper->generate('user', '#');
        $this->assertEquals($form->getFormHTML(array(
            'username' => 'Nom d\'utilisateur',
            'firstname' => 'Prénom'
        )), $result);
    }
}