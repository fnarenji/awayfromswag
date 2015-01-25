<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 13/01/15
 * Time: 15:39
 */

namespace app\controllers;

use app\exceptions\PasswordNotSameExceptionException;
use app\helpers\PrivacyCalculator;
use app\models\EventModel;
use app\models\FriendModel;
use app\models\UserModel;
use SwagFramework\Exceptions\InputNotSetException;
use SwagFramework\Exceptions\MissingParamsException;
use SwagFramework\Exceptions\NoUserFoundException;
use SwagFramework\Form\Field\InputField;
use SwagFramework\Form\Field\LabelField;
use SwagFramework\Form\Form;
use SwagFramework\Helpers\Authentication;
use SwagFramework\Helpers\Input;
use SwagFramework\Helpers\MailUtil;
use SwagFramework\mvc\Controller;

class UserController extends Controller
{
    /**
     * @var UserModel the user model duh
     */
    private $userModel;

    /**
     * @var FriendModel the user model duh
     */
    private $friendModel;

    public function __construct()
    {
        $this->userModel = $this->loadModel('User');
        parent::__construct();
    }

    public function index()
    {
        $this->getView()->redirect('/');
    }

    public function profile()
    {
        try {
            $name = $this->getParams();

            $user = $this->userModel->getUserByUserName($name[0]);

            if (empty($user)) {
                throw new NoUserFoundException($name[0]);
            }

            $user['mailHash'] = md5($user['mail']);

            $birthday = new \DateTime($user['birthday']);
            $today = new \DateTime();
            $user['age'] = $birthday->diff($today)->format('%Y');

            $registerDate = new \DateTime($user['registerdate']);
            $user['registerDateFormat'] = $registerDate->format('d/m/Y');

            $user = array_merge($user, PrivacyCalculator::calculate($user['id']));

            $model = new EventModel();
            $events = $model->getEventsForUser($user['id']);

            foreach($events as $key => $value){
                $events[$key]['eventtime'] = substr($events[$key]['eventtime'], 0, 10);
            }

            $this->getView()->render('user/profile', ['profile' => $user, 'events' => $events ]);

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

                Authentication::getInstance()->setAuthenticated($username, $validAuth['id'],
                    [
                        'mailHash' => $validAuth['mailHash'],
                        'lastname' => $validAuth['lastname'],
                        'firstname' => $validAuth['firstname'],
                        'accessLevel' => $validAuth['accesslevel']
                ]);

                $this->getView()->redirect('/');
            } else {
                // TODO POPUP WRONG CREDENTIALS MESSAGE

                $this->getView()->redirect('/');
            }
        } catch (InputNotSetException $e) {
            //throw $e;
            $this->getView()->redirect('/');
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
            } else throw $e;

        }

        if (!empty($errors))
            $this->getView()->render('user/register', ['user' => $user, 'errors' => $errors]);
        else {
            $this->getView()->redirect('/');
        }
    }

    public function account()
    {
        try {
            $params = $this->getParams(true);

            if (!empty($params) && Authentication::getInstance()->getOptionOr('accessLevel', 0)) {
                $user = $this->userModel->getUser($params[0]);
            } else {
                $user = $this->userModel->getUser(Authentication::getInstance()->getUserId());
            }

            if (empty($user)) {
                throw new NoUserFoundException(Authentication::getInstance()->getUserName());
            }

            $user['mailHash'] = md5($user['mail']);

            $birthday = new \DateTime($user['birthday']);
            $today = new \DateTime();
            $user['age'] = $birthday->diff($today)->format('%Y');

            $registerDate = new \DateTime($user['registerdate']);
            $user['registerDateFormat'] = $registerDate->format('d/m/Y');

            $user = array_merge($user, PrivacyCalculator::calculate($user['id']));

            $this->getView()->render('user/account', $user);

        } catch (NoUserFoundException $e) {
            // TODO POPUP
            $e->getMessage();
            $this->getView()->render('/home/index');
        }
    }

    public function accountPOST()
    {
        // HARDCODE
        $privacyValues = [
            'jobPrivacy' => 1,
            'websitePrivacy' => 2,
            'facebookuriPrivacy' => 4,
            'skypePrivacy' => 8,
            'twitterPrivacy' => 16,
            'phonenumberPrivacy' => 32,
            'mailPrivacy' => 64];

        $input = new Input();
        $toModify = $input->getPost();

        try {

            if (!empty($params) && Authentication::getInstance()->getOptionOr('accessLevel', 0)) {
                $user = $this->userModel->getUser($toModify['id']);
            } else {
                $user = $this->userModel->getUser(Authentication::getInstance()->getUserId());
            }

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
            $this->getView()->redirect('/user/profile/' . $toModify['username']);
            //$this->getView()->render('home/index');
        } catch (NoUserFoundException $e){
            $e->getMessage();
            $this->getView()->render('/home/index');
        }
    }

    public function json_list()
    {
        echo json_encode($this->userModel->getAllUsersFullNames());
    }

    public function calendar()
    {
        $this->getView()->render('/user/calendar');
    }

    public function getcalendar()
    {
        include 'app/views/user/calendarDisplay.php';
    }

    public function all()
    {
        $page = 0;
        if (!empty($this->getParams(true)))
            $page = (int)$this->getParams()[0] - 1;

        $total = $this->userModel->count()['nb'];
        $total = (int)ceil($total / 5);

        $userList = $this->userModel->getAllUsers($page * 5, 5);
        $userFriendList = $this->userModel->getAllFriends();

        foreach($userList as $key => $value)
        {
            foreach($userFriendList as $relation)
            {
                if(in_array($value['id'], $relation))
                {
                    $userList[$key]['addFriend'] = false;
                    break;
                }
            }
            if(!isset($userList[$key]['addFriend']))
                $userList[$key]['addFriend'] = true;
        }

        $this->getView()->render('user/all',
            ['users' => $userList, 'page' => ['actual' => $page + 1, 'total' => $total]]);
    }

    public function add()
    {
        try
        {
            $id = $this->getParams()[0];

            $this->userModel->addToFriend($id);
            $this->getView()->redirect('/');
        }
        catch(MissingParamsException $e)
        {
            $e->getMessage();
        }
    }

    public function requestReset()
    {
        $this->getView()->render('user/requestReset');
    }

    public function updateFriend()
    {
        $id2 = $this->getParams()[0];
        $this->friendModel = $this->loadModel('Friend');
        $this->friendModel->updateFriend(Authentication::getInstance()->getUserId(), $id2);
        $this->friends();
    }

    public function friends()
    {
        $this->friendModel = $this->loadModel('Friend');
        $user = $this->friendModel->getAllFriendById(Authentication::getInstance()->getUserId());
        $this->getView()->render('user/friend',['users' => $user]);
    }

    public function deleteFriend(){
        $id2 = $this->getParams()[0];
        $this->friendModel = $this->loadModel('Friend');
        $this->friendModel->deleteFriend(Authentication::getInstance()->getUserId(),$id2);
        $this->friends();
    }

    public function myevent()
    {
        $model = $this->loadModel('Event');
        $myevent = $model->getEventsForUser(Authentication::getInstance()->getUserId());
        $this->getView()->render('user/myevent',['events' => $myevent]);
    }

    public function validate()
    {
        if (empty($this->getParams(true))) {
            $this->getView()->redirect('/');
            die();
        }

        $token = $this->getParams()[0];
        $this->userModel->validateToken($token);
        $this->getView()->redirect('/');
    }

    public function reset()
    {
        $form = new Form('/user/reset');
        $form->addField(new LabelField('mail'));
        $form->addField(new InputField('mail', ['type' => 'text']));
        $form->addField(new InputField('submit', ['type' => 'submit']));

        $html = $form->getFormHTML([
            'mail' => 'Adresse mail'
        ]);

        $this->getView()->render('user/reset', ['form' => $html]);
    }

    public function resetPOST()
    {
        $form = new Form('/user/reset');
        $form->addField(new LabelField('mail'));
        $form->addField(new InputField('mail', ['type' => 'text']));
        $form->addField(new InputField('submit', ['type' => 'submit']));

        $result = $form->validate([
            'mail' => 'Adresse mail'
        ]);

        $user = $this->userModel->getUserByMail($result['mail']);

        if (empty($user)) {
            throw new NoUserFoundException($result['mail']);
        }

        $token = hash('md5', uniqid());

        $this->userModel->setReset($user['id'], $token);

        $message = 'Cliquez ici pour changer votre mot de passe : ';
        $message .= CR . 'https://srv0.sknz.info:3735/user/resetpasswd/' . $token;

        MailUtil::send($user['mail'], 'AwayFromSecurity : RESET PASSWORD', $message);

        $this->getView()->redirect('/');
    }

    public function passwd()
    {
        $token = $this->getParams()[0];

        $id = $this->userModel->getReset($token);

        if (empty($id)) {
            throw new NoUserFoundException($token);
        }

        $form = new Form('/user/passwd');
        $form->addField(new LabelField('passwd1'));
        $form->addField(new LabelField('passwd2'));
        $form->addField(new InputField('passwd1', ['type' => 'password']));
        $form->addField(new InputField('passwd2', ['type' => 'password']));
        $form->addField(new InputField('submit', ['type' => 'submit']));

        $html = $form->getFormHTML([
            'passwd1' => 'Nouveau mot de passe',
            'passwd2' => 'Confirmier le mot de passe'
        ]);

        $this->getView()->render('user/passwd', ['form' => $html]);
    }

    public function passwdPOST()
    {
        $id = $this->userModel->getReset($token);

        if (empty($id)) {
            throw new NoUserFoundException($token);
        }

        $form = new Form('/user/resetpasswd');
        $form->addField(new LabelField('passwd1'));
        $form->addField(new LabelField('passwd2'));
        $form->addField(new InputField('passwd1', ['type' => 'password']));
        $form->addField(new InputField('passwd2', ['type' => 'password']));
        $form->addField(new InputField('submit', ['type' => 'submit']));

        $result = $form->validate([
            'passwd1' => 'Nouveau mot de passe',
            'passwd2' => 'Confirmer le mot de passe'
        ]);

        if ($result['passwd1'] != $result['passwd2']) {
            throw new PasswordNotSameExceptionException();
        }

        $this->userModel->updatePassword($id, $result['passwd1']);

        $this->getView()->redirect('/');
    }
}