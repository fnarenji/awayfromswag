<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 05/01/15
 * Time: 21:35
 */

namespace app\controllers;


use SwagFramework\mvc\Controller;

class ErrorsController extends Controller {
    public function err404() {
        $this->getView()->render('errors/err404');
    }
}