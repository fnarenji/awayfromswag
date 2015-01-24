<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 23/01/15
 * Time: 15:30
 */

namespace tests\Helpers;


use SwagFramework\Helpers\FormHelper;

class FormTest extends \PHPUnit_Framework_TestCase
{
    function __construct()
    {
    }

    public function testForm()
    {
        $this->userForm();
    }

    private function userForm()
    {
        $result = '<form method="POST" action="#" id="" class="pure-form pure-form-aligned centered">
    <div class="pure-controls">
        <input name="id" type="hidden" />
    </div>
    <div class="pure-control-group">
        <label for="username">Nom d\'utilisateur</label>
        <input name="username" type="text" />
    </div>
    <div class="pure-control-group">
        <label for="firstname">Prénom</label>
        <input name="firstname" type="text" />
    </div>
    <div class="pure-controls">
        <input name="submit" type="submit" value="Envoyer" class="pure-button pure-button-primary" />
    </div>
</form>';

        $form = FormHelper::generate('user', '#');
        $this->assertEquals($form->getFormHTML(array(
            'username' => 'Nom d\'utilisateur',
            'firstname' => 'Prénom'
        )), $result);
    }
}