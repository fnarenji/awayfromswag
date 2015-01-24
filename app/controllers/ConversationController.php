<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 16/01/15
 * Time: 10:10
 */

namespace app\controllers;

use app\exceptions\ConversationNotFoundException;
use app\exceptions\NotAuthenticatedException;
use app\models\ConversationModel;
use SwagFramework\Config\ConversationConfig;
use SwagFramework\Exceptions\FileNotFoundException;
use SwagFramework\Helpers\Authentication;
use SwagFramework\mvc\Controller;

class ConversationController extends Controller
{

    /**
     * @var ConversationModel
     */
    private $conversationModel;

    /**
     * @var \SwagFramework\Config\ConversationConfig
     */
    private $conversationConfig;

    function __construct()
    {
        parent::__construct();
        $this->conversationModel = $this->loadModel('Conversation');
        $this->conversationConfig = ConversationConfig::parseFromFile();
    }

    public function index()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $conversations = $this->conversationModel->getUser(Authentication::getInstance()->getUserId());

        $this->getView()->render('conversation/index', ['conversations' => $conversations]);
    }

    public function show()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $id = (int)$this->getParams()[0];
        $conversation = $this->conversationModel->get($id);

        if (empty($conversation)) {
            throw new ConversationNotFoundException($id);
        }

        $file = $this->conversationConfig->getPath() . $id . '.xml';

        if (!file_exists($file)) {
            throw new FileNotFoundException($file);
        }

        $messages = new \SimpleXMLElement(file_get_contents($file));

        $this->getView()->render('conversation/show', ['conversation' => $conversation, 'messages' => $messages]);
    }

    public function create()
    {
        $this->getView()->render('conversation/create');
    }
}