<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 30/12/14
 * Time: 20:47
 */

namespace tests\Helpers;

use SwagFramework\Helpers\Popup;

class PopupTest extends \PHPUnit_Framework_TestCase
{
    private $popup;

    public function testPopup()
    {
        $this->Popup();
    }

    private function Popup()
    {
        $this->popup = new Popup();

        // NO POPUP
        $this->assertEquals($this->popup->getAll(), array());

        // 1 POPUP
        $new = array(
            'title' => 'Title',
            'message' => 'Message',
            'type' => 'success'
        );

        $this->popup->set($new['title'], $new['message'], $new['type']);
        $this->assertEquals($this->popup->getAll(), array(
            $new
        ));

        // 2 POPUP
        $new2 = array(
            'title' => 'Title2',
            'message' => 'Message2',
            'type' => 'success'
        );

        $this->popup->set($new2['title'], $new2['message'], $new2['type']);
        $this->assertEquals($this->popup->getAll(), array(
            $new,
            $new2
        ));
    }
}