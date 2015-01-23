<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 01/01/15
 * Time: 20:27
 */

namespace app\controlers;

use SwagFramework\mvc\Controler;

class HomeControler extends Controler
{
    public function index()
    {
        $this->getView()->render('home/index', array('logged' => false));
    }
}