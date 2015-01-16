<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 13/01/15
 * Time: 15:39
 */

namespace app\controllers;


use SwagFramework\mvc\Controller;

class UserController extends Controller
{
    public function index()
    {
        $this->getView()->render('user/index');
    }

    public function profile()
    {
        $name = $this->getParams();

        $userModel = $this->loadModel('User');
        $user = $userModel->getUserByName($name[0]);


        $this->getView()->render('user/profile', $user);
    }
}