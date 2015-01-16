<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 13/01/15
 * Time: 15:39
 */

namespace app\controllers;


use SwagFramework\Exceptions\MissingParamsException;
use SwagFramework\Exceptions\NoUserFoundException;
use SwagFramework\mvc\Controller;

class UserController extends Controller
{
    public function index()
    {
        $this->getView()->render('user/index');
    }

    public function profile()
    {
       try
       {
           $name = $this->getParams();
           $userModel = $this->loadModel('User');
           $user = $userModel->getUserByName($name[0]);

            if(empty($user))
                throw new NoUserFoundException($name[0]);

           $user = $user[0];

           $user['mailHash'] = md5($user['mail']);
           $this->getView()->render('user/profile', $user);
       }
       catch(MissingParamsException $e)
       {
           // TODO POPUP
           $this->getView()->render('/home/index');
       }
       catch(NoUserFoundException $e)
       {
           // TODO POPUP
           $this->getView()->render('/home/index');
       }
    }
}