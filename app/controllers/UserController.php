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
use SwagFramework\Helpers\Authentication;
use SwagFramework\Helpers\FormHelper;
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
        $this->getView()->redirect('/');
    }

    public function authPOST()
    {
        try {
            $username = Input::post('username');
            $password = Input::post('password');

            $validAuth = $this->userModel->validateAuthentication($username, $password);

            if (!empty($validAuth)) {
                Authentication::getInstance()->setAuthenticated($username, $validAuth['id']);
                $this->getView()->redirect('/');
            } else {
                // TODO POPUP WRONG CREDENTIALS MESSAGE

                $this->getView()->redirect('/');
            }
        } catch (InputNotSetException $e) {
            throw $e;
        }
    }

    public function disconnect()
    {
        Authentication::getInstance()->disconnect();
        $this->getView()->redirect('/');
    }

    public function register()
    {
        $form = new FormHelper();
        $form = $form->generate('user', 'user/register');
        $form->setClass('pure-form pure-form-stacked');

        $formHtml = $form->getFormHTML([
            'username' => 'Nom d\'utilisateur',
            'password' => 'Mot de passe',
            'firstname' => 'Prénom',
            'lastname' => 'Nom',
            'birthday' => 'Date de naissance',
            'mail' => 'Adresse e-mail',
            'phonenumber' => 'Téléphone',
            'twitter' => 'Twitter',
            'skype' => 'Skype',
            'facebookuri' => 'Facebook',
            'website' => 'Site web',
            'job' => 'Activité',
            'description' => 'Description',
            'privacy' => 'Options de vie privée',
            'mailnotifications' => 'Recevoir les emails'
        ]);

        $this->getView()->render('user/register', ['formHtml' => $formHtml]);
    }

    public function account()
    {
        try {
            $user = $this->userModel->getUser(Authentication::getInstance()->getUserId());
            if (empty($user)) {
                throw new NoUserFoundException(Authentication::getInstance()->getUserName());
            }

            $user['mailHash'] = md5($user['mail']);

            $birthday = new \DateTime($user['birthday']);
            $today = new \DateTime();
            $user['age'] = $birthday->diff($today)->format('%Y');

            $registerDate = new \DateTime($user['registerdate']);
            $user['registerDateFormat'] = $registerDate->format('d/m/Y');




            // WOW SUCH ALGORITHM MUCH SKILL
            $privacy = (int)$user['privacy'];

            // init
            $x = 14;

            foreach($user as $key => $value){
                if($key == 'privacy') break;

                $exp = 2**$x;

                if($exp <= $privacy){
                    $user[$key . 'Privacy'] = true;
                    $privacy -= $exp;
                } else {
                    $user[$key . 'Privacy'] = false;
                }

                --$x;
            }
            var_dump($user);

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