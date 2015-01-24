<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 13/01/15
 * Time: 15:39
 */

namespace app\controllers;

use app\helpers\PrivacyCalculator;
use app\models\UserModel;
use SwagFramework\Exceptions\InputNotSetException;
use SwagFramework\Exceptions\MissingParamsException;
use SwagFramework\Exceptions\NoUserFoundException;
use SwagFramework\Form\Field\InputField;
use SwagFramework\Form\Field\TextAreaField;
use SwagFramework\Form\Form;
use SwagFramework\Helpers\Authentication;
use SwagFramework\Helpers\Input;
use SwagFramework\mvc\Controller;

class UserController extends Controller
{
    /**
     * @var UserModel the user model duh
     */
    private $userModel;

    public function __construct()
    {
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
        $formHtml = $this->getRegisterForm()->getFormHTML([
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
            'mailnotifications' => 'Recevoir les emails',
            'submit' => 'S\'inscrire !'
        ]);

        $this->getView()->render('user/register', ['formHtml' => $formHtml]);
    }

    private function getRegisterForm()
    {
        $form = new Form('/user/register');

        $f = function ($field, array $attr = []) use ($form) {
            $form->addField(new InputField($field, $attr));
        };

        $form->setClass('pure-form pure-form-stacked');
        $f('username', ['required' => null]);
        $f('password', ['required' => null, 'type' => 'password']);
        $f('firstname', ['required' => null]);
        $f('lastname', ['required' => null]);
        $f('birthday', ['required' => null, 'class' => 'datepicker']);
        $f('mail', ['required' => null, 'type' => 'email']);
        $f('phonenumber', ['type' => 'tel']);
        $f('twitter');
        $f('skype');
        $f('facebookuri', ['type' => 'url']);
        $f('website', ['type' => 'url']);
        $f('job');
        $form->addField(new TextAreaField('description', ['required' => null]));
        $f('submit', ['type' => 'submit', 'value' => 'Valider !']);

        return $form;
    }

    public function registerPOST()
    {
        $form = $this->getRegisterForm();
        $safeInput = $form->validate(['username' => true,
            'password' => true,
            'firstname' => true,
            'lastname' => true,
            'birthday' => true,
            'mail' => true,
            'phonenumber' => false,
            'twitter' => false,
            'skype' => false,
            'facebookuri' => false,
            'website' => false,
            'job' => true,
            'description' => true,
            'privacy' => false,
            'mailnotifications' => false,
            'submit' => 'S\'inscrire !']);

        $this->userModel->insertUser($safeInput);
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

            $user = array_merge($user, PrivacyCalculator::calculate(Authentication::getInstance()->getUserId()));

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

    public function accountPOST()
    {
        // HARDCODE
        $privacyValues = array(
            "jobPrivacy" => 1,
            "websitePrivacy" => 2,
            "facebookuriPrivacy" => 4,
            "skypePrivacy" => 8,
            "twitterPrivacy" => 16,
            "phonenumberPrivacy" => 32,
            "mailPrivacy" => 64);

        $input = new Input();
        $toModify = $input->getPost();

        try {

            $user = $this->userModel->getUser(Authentication::getInstance()->getUserId());
            if (empty($user)) {
                throw new NoUserFoundException(Authentication::getInstance()->getUserName());
            }

            $privacyUser = PrivacyCalculator::calculate($user['id']);

            foreach($privacyValues as $key => $value){
                if (!isset($toModify[$key]) && $privacyUser[$key] == true)
                    $user['privacy'] -= $value;
                else if (isset($toModify[$key]) && $privacyUser[$key] == false){
                    $user['privacy'] += $value;
                }
                if (isset($toModify[$key]))
                    unset($toModify[$key]);

            }

            foreach($toModify as $key => $value){
                if($user[$key] != $value)
                    $user[$key] = $value;
            }

            $toModify['privacy'] = $user['privacy'];
            $toModify['id'] = $user['id'];

            $mod = [];
            foreach ($toModify as $m) {
                array_push($mod, $m);
            }

            $this->userModel->updateUser($mod);
            $this->getView()->render('home/index');
        } catch (NoUserFoundException $e){
            $e->getMessage();
            $this->getView()->render('/home/index');
        }
    }
}