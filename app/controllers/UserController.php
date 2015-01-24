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

            $this->getView()->render('user/profile', ['profile' => $user]);
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
                Authentication::getInstance()->setAuthenticated($username, $validAuth['id'], ['mailHash' => $validAuth['mailHash']]);
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
        $this->getView()->render('user/register');
    }

    public function registerPOST()
    {
        $user = [
            'username' => Input::post('username'),
            'password' => Input::post('password'),
            'firstname' => Input::post('firstname'),
            'lastname' => Input::post('lastname'),
            'birthday' => Input::post('birthday'),
            'mail' => Input::post('mail'),
            'phonenumber' => Input::post('phonenumber', true),
            'twitter' => Input::post('twitter', true),
            'skype' => Input::post('skype', true),
            'facebookuri' => Input::post('facebookuri', true),
            'website' => Input::post('website', true),
            'job' => Input::post('job'),
            'description' => Input::post('description'),
            'privacy' => 0,
            'mailnotifications' => Input::post('mailnotifications', true) == 'on' ? 1 : 0,
            'accesslevel' => 0
        ];

        // LES FLAGS C TROP SWAG
        $privacySettings = ['birthday', 'mail', 'phonenumber', 'twitter', 'skype', 'facebookuri', 'website', 'job'];
        for ($i = 0; $i < sizeof($privacySettings); ++$i)
            if (Input::post($privacySettings[$i] . 'Private', true) == 'on')
                $user['privacy'] |= 0b000000000000001 << $i;

        $errors = [];
        try {
            $this->userModel->insertUser($user);
        } catch (\PDOException $e) {
            $match = [];
            if (preg_match('/SQLSTATE\[23000]: Integrity constraint violation: 1062 Duplicate entry \'(?P<value>.*)\' for key \'(?P<field>.*)_UNIQUE\'/', $e->getMessage(), $match)) {
                switch ($match['field']) {
                    case 'username':
                        $errors[] = 'Ce nom d\'utilisateur est déjà pris !';
                        break;
                    default:
                        $errors[] = 'Unknown database error.';
                }
            }

        } catch (\Exception $e) {

        }
        if (!empty($errors))
            $this->getView()->render('user/register', ['user' => $user, 'errors' => $errors]);
        else
            $this->getView()->redirect('/');
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

            $this->userModel->updateUser($toModify);
            $this->getView()->render('home/index');
        } catch (NoUserFoundException $e){
            $e->getMessage();
            $this->getView()->render('/home/index');
        }
    }
}