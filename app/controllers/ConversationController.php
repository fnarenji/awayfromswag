<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 16/01/15
 * Time: 10:10
 */

namespace app\controllers;

use app\exceptions\NotAuthenticatedException;
use app\models\ConversationModel;
use SwagFramework\Config\ConversationConfig;
use SwagFramework\Helpers\Authentication;
use SwagFramework\Helpers\Input;
use SwagFramework\mvc\Controller;

class ConversationController extends Controller
{

    /**
     * @var ConversationModel
     */
    private $conversationModel;

    function __construct()
    {
        parent::__construct();
        $this->conversationModel = $this->loadModel('Conversation');
    }

    public function index()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $conversations = $this->conversationModel->getForUser(Authentication::getInstance()->getUserId());

        $this->getView()->render('conversation/index', ['conversations' => $conversations]);
    }

    public function show()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $id = (int)$this->getParams()[0];

        $conversation = $this->conversationModel->getConversation($id);
        $this->getView()->render('conversation/show', ['conversation' => $conversation]);
    }

    public function create()
    {
        $this->getView()->render('conversation/create');
    }

    public function createPOST()
    {
        $message = Input::post('message');

        $newConversationId = $this->conversationModel->createConversation(Input::post('title'));
        $userModel = $this->loadModel('User');

        foreach (explode(', ', Input::post('participations')) as $participation) {
            $userId = $userModel->getUserFullNameLike($participation);

            $this->conversationModel->addUserToConversation($newConversationId, $userId);
        }

        $this->conversationModel->newMessage($newConversationId, $message);
        //$this->getView()->redirect('/');
    }
}