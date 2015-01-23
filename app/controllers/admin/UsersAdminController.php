<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 16/01/15
 * Time: 10:10
 */

namespace app\controllers\admin;


use SwagFramework\mvc\Controller;

class UsersAdminController extends Controller
{

    /**
     * @var \app\models\UserModel
     */
    private $model;

    public function index()
    {
        $this->model = $this->loadModel('User');
        $allUser = $this->model->getAllUsers();
        $this->getView()->render('admin/users', ['alluser' => $allUser]);
    }

    public function delete()
    {
        $iduser = (int)$this->getParams()[0];
        $this->model->deleteUser($iduser);
    }

    public function update()
    {

        $iduser = (int)$this->getParams()[0];
        $username = Input::get('username');
        $firstname = Input::get('firstname');
        $lastname = Input::get('lastname');
        $mail = Input::get('mail');
        $password = sha1(Input::get('password'));

        $this->model->updateAdminUser($iduser, ['username' => $username,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'mail' => $mail,
            'password' => $password]);
    }
}