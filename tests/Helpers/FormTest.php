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

    private function userForm()
    {
        $result = '<form method="POST" action="">
    <input name="id" type="hidden" />
    <label for="username">Nom d\'utilisateur</label>
    <input name="username" type="text" />
    <input name="password" type="text" />
    <label for="firstname">Prénom</label>
    <input name="firstname" type="text" />
    <input name="lastname" type="text" />
    <input name="birthday" type="text" />
    <input name="registerdate" type="text" />
    <input name="lastconnectiontime" type="text" />
    <input name="mail" type="text" />
    <input name="phonenumber" type="text" />
    <input name="twitter" type="text" />
    <input name="skype" type="text" />
    <input name="facebookuri" type="text" />
    <input name="website" type="text" />
    <input name="job" type="text" />
    <input name="description" type="text" />
    <input name="privacy" type="text" />
    <input name="mailnotifications" type="text" />
    <input name="accesslevel" type="text" />
</form>';

        $form = $this->helper->generate('user', '#');
        $this->assertEquals($form->getFormHTML(array(
            'username' => 'Nom d\'utilisateur',
            'firstname' => 'Prénom'
        )), $result);
    }

    public function testForm()
    {
        $this->userForm();
    }
}