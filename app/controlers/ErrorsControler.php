<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 05/01/15
 * Time: 21:35
 */

namespace app\controlers;


use SwagFramework\mvc\Controler;

class ErrorsControler extends Controler
{
    public function err404()
    {
        $this->getView()->render('errors/err404');
    }
}