<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 25/01/15
 * Time: 15:48
 */

namespace app\controllers;

use SwagFramework\Helpers\Input;
use SwagFramework\mvc\Controller;

class PartenersController extends Controller {

    public function index(){
        $this->getView()->render('parteners/index');
    }

}