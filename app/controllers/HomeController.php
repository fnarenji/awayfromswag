<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 01/01/15
 * Time: 20:27
 */

namespace app\controllers;

use app\helpers\Authentication;
use SwagFramework\mvc\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->getView()->render('home/index');
    }
}