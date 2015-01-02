<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 30/12/14
 * Time: 21:04
 */

namespace tests\Tools;


use SwagFramework\Tools\Session;

class SessionTest extends \PHPUnit_Framework_TestCase
{
    private $session;

    function __construct()
    {
        $this->session = new Session();
    }

    public function testSession()
    {
        // STRING
        $this->session->set('test', 'testValue');
        $this->assertEquals($this->session->get('test'), 'testValue');

        // ARRAY
        $this->session->set('test', array(
            'test' => 'testvalue'
        ));
        $this->assertEquals($this->session->get('test'), array(
            'test' => 'testvalue'
        ));
    }
}