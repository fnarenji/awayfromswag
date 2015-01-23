<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 13/01/15
 * Time: 15:39
 */

namespace app\controlers;


use SwagFramework\Exceptions\InputNotSetException;
use SwagFramework\Exceptions\MissingParamsException;
use SwagFramework\Exceptions\NoUserFoundException;
use SwagFramework\mvc\Controler;

class UserControler extends Controler
{
    public function index()
    {
        $this->getView()->render('user/index');
    }

    public function profile()
    {
        try {
            $name = $this->getParams();
            $userModel = $this->loadModel('User');
            $user = $userModel->getUserByName($name[0]);

            if (empty($user)) {
                throw new NoUserFoundException($name[0]);
            }

            $user = $user[0];

            $user['mailHash'] = md5($user['mail']);

            $birthday = new \DateTime($user['birthday']);
            $today = new \DateTime();
            $user['age'] = $birthday->diff($today)->format('%Y');

            $registerDate = new \DateTime($user['registerdate']);
            $user['registerDateFormat'] = $registerDate->format('d/m/Y');

            $this->getView()->render('user/profile', $user);
        } catch (MissingParamsException $e) {
            // TODO POPUP
            $this->getView()->render('/home/index');
        } catch (NoUserFoundException $e) {
            // TODO POPUP
            $this->getView()->render('/home/index');
        }
    }

    public function auth()
    {
        try {
            $userModel = $this->loadModel('User');
            $input = $this->helpers->input;

            $username = $input->post('username');
            $password = sha1($input->post('password'));

            $validAuth = $userModel->validateAuthentication($username, $password);

            if ($validAuth) {
                $_SESSION['user'] = $username;
                $_SESSION['id'] = $validAuth;
                $_SESSION['authDate'] = new \DateTime();
                $this->getView()->render('/home/index');
            } else {
                // TODO POPUP
                $this->getView()->render('/home/index');
            }
        } catch (InputNotSetException $e) {
            throw $e;
        }
    }
}