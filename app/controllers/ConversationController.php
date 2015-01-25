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

        if (empty($this->getParams(true)))
            $this->getView()->redirect('/conversation');

        $id = (int)$this->getParams()[0];

        $conversation = $this->conversationModel->getConversation($id);
        $this->getView()->render('conversation/show', ['conversation' => $conversation]);
    }

    public function showPOST()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        if (empty($this->getParams(true)))
            $this->getView()->redirect('/conversation');

        $id = (int)$this->getParams()[0];
        $message = Input::post('message', true);

        if (!empty($message))
            $this->conversationModel->newMessage($this->getParams()[0], $message);

        $this->getView()->redirect('/conversation/show/' . $id);
    }

    public function create()
    {
        $dest = null;
        if (!empty($this->getParams(true))) {
            $dest = $this->getParams()[0];
            $userModel = $this->loadModel('User');
            $dest = $userModel->getUserFullName($dest) . ', ';
        }
        $this->getView()->render('conversation/create', ['dest' => $dest]);
    }

    public function createPOST()
    {
        $message = Input::post('message');

        $userModel = $this->loadModel('User');
        $participationIds = [];

        foreach (explode(', ', Input::post('participations')) as $participation) {
            $userId = $userModel->getUserFullNameLike($participation)['id'];

            $participationIds[] = (int)$userId;
        }

        if ($existingConversationId = $this->conversationModel->conversationExistsBetween($participationIds)) {
            $this->conversationModel->newMessage($existingConversationId, $message);
            $this->getView()->redirect('/conversation/show/' . $existingConversationId);
            die();
        }

        $newConversationId = $this->conversationModel->createConversation(Input::post('title'));

        foreach ($participationIds as $participationId)
            $this->conversationModel->addUserToConversation($newConversationId, $participationId);

        $this->conversationModel->addUserToConversation($newConversationId, Authentication::getInstance()->getUserId());

        $this->conversationModel->newMessage($newConversationId, $message);
        $this->getView()->redirect('/conversation/show/' . $newConversationId);
    }

    public function add()
    {
        $this->getView()->redirect('/conversation');
    }

    public function addPOST()
    {
        if (empty($this->getParams(true)))
            $this->getView()->redirect('/conversation');

        $conversationId = (int)$this->getParams()[0];

        $userModel = $this->loadModel('User');

        foreach (explode(', ', Input::post('participations')) as $participation) {
            $userId = $userModel->getUserFullNameLike($participation)['id'];

            $this->conversationModel->addUserToConversation($conversationId, $userId);
        }
        $this->getView()->redirect('/conversation/show/' . $conversationId);
    }

    public function quit()
    {
        if (empty($this->getParams(true)))
            $this->getView()->redirect('/conversation');

        $conversationId = (int)$this->getParams()[0];
        $this->conversationModel->removeFromConversation($conversationId);
        $this->getView()->redirect('/conversation');
    }
}