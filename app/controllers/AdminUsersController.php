<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 16/01/15
 * Time: 10:10
 */

namespace app\controllers;

use app\helpers\PrivacyCalculator;
use app\models\ConversationModel;
use SwagFramework\Exceptions\InputNotSetException;
use SwagFramework\Exceptions\MissingParamsException;
use SwagFramework\Exceptions\NoUserFoundException;
use SwagFramework\Helpers\Authentication;
use SwagFramework\Helpers\Input;
use SwagFramework\mvc\Controller;

class AdminUsersController extends Controller
{

    /**
     * @var \app\models\UserModel
     */
    private $userModel;

    public function index()
    {
        $this->userModel = $this->loadModel('User');
        $allUser = $this->userModel->getAllUsers();
        $input = new Input();

        $this->getView()->render('admin/users', ['users' => $allUser]);
    }

    public function user()
    {
        try {
            $this->userModel = $this->loadModel('User');
            $name = $this->getParams();

            $user = $this->userModel->getUser($name[0]);

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
            $this->getView()->render('admin/user', $user);

        } catch (MissingParamsException $e) {
            // TODO POPUP
            $this->getView()->render('/home/index');
        } catch (NoUserFoundException $e) {
            // TODO POPUP
            $this->getView()->render('/home/index');
        }
    }

    public function userPOST(){
        
    }

    public function delete()
    {
        $this->userModel = $this->loadModel('User');
        $iduser = $this->getParams()[0];
        $this->userModel->deleteUser($iduser);
        $conversationModel = new ConversationModel();
        $conversationModel;
        $this->index();
    }

    public function update()
    {

        $this->userModel = $this->loadModel('User');

        $iduser = (int)$this->getParams()[0];
        $username = Input::get('username');
        $firstname = Input::get('firstname');
        $lastname = Input::get('lastname');
        $mail = Input::get('mail');
        $password = sha1(Input::get('password'));

        $this->userModel->updateAdminUser($iduser, [
            'username' => $username,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'mail' => $mail,
            'password' => $password
        ]);
    }
}