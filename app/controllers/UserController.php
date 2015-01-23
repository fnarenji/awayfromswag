<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 13/01/15
 * Time: 15:39
 */

namespace app\controllers;


use app\models\UserModel;
use SwagFramework\Exceptions\InputNotSetException;
use SwagFramework\Exceptions\MissingParamsException;
use SwagFramework\Exceptions\NoUserFoundException;
use SwagFramework\Helpers\Input;
use SwagFramework\mvc\Controller;

class UserController extends Controller
{
    /**
     * @var UserModel the user model duh
     */
    private $userModel;

    public function __construct() {
        $this->userModel = $this->loadModel('User');
        parent::__construct();
    }
    
    public function index()
    {
        $this->getView()->render('user/index');
    }

    public function profile()
    {
        try {
            $name = $this->getParams();
            
            $user = $this->userModel->getUserByName($name[0]);

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
        $this->getView()->render('/home/index');
    }

    public function authPOST()
    {
        try {
            $input = $this->helpers->input;

            $username = $input->post('username');
            $password = sha1($input->post('password'));

            $validAuth = $this->userModel->validateAuthentication($username, $password);

            if ($validAuth) {
                $_SESSION['user'] = $username;
                $_SESSION['id'] = $validAuth[0]['id'];
                $_SESSION['authDate'] = new \DateTime();

                //$this->getView()->render('/home/index');
                $this->getView()->redirect('/');
            } else {
                // TODO POPUP

                $this->getView()->render('/home/index');
            }
        } catch (InputNotSetException $e) {
            throw $e;
        }
    }

    public function account()
    {
        try {
            $userModel = $this->loadModel('User');
            $input = new Input();
            $user = $userModel->getUser($input->session('id'));
            if (empty($user)) {
                throw new NoUserFoundException($input->session('user'));
            }

            $user = $user[0];

            $user['mailHash'] = md5($user['mail']);

            $birthday = new \DateTime($user['birthday']);
            $today = new \DateTime();
            $user['age'] = $birthday->diff($today)->format('%Y');

            $registerDate = new \DateTime($user['registerdate']);
            $user['registerDateFormat'] = $registerDate->format('d/m/Y');

            $this->getView()->render('user/account', $user);
        } catch (MissingParamsException $e) {
            // TODO POPUP
            $e->getMessage();
            $this->getView()->render('/home/index');
        } catch (NoUserFoundException $e) {
            // TODO POPUP
            $e->getMessage();
            $this->getView()->render('/home/index');
        }
    }
}