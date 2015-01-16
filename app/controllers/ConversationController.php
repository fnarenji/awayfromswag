<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 16/01/15
 * Time: 10:10
 */

namespace app\controllers;


use SwagFramework\Config\ConversationConfig;
use SwagFramework\Exceptions\ConversationNotFoundException;
use SwagFramework\Exceptions\FileNotFoundException;
use SwagFramework\mvc\Controller;

class ConversationController extends Controller
{

    /**
     * @var \app\models\ChatModel
     */
    private $conversationModel;

    /**
     * @var \SwagFramework\Config\ConversationConfig
     */
    private $conversationConfig;

    function __construct()
    {
        $this->conversationModel = $this->loadModel('Chat');
        $this->conversationConfig = ConversationConfig::parseFromFile();
    }

    public function index()
    {
        $discussions = $this->conversationModel->getAll();

        $message = '';
        if (empty($discussions)) {
            $message = 'Vous n\'avez pas de conversations';
        }

        $this->getView()->render('conversations/index', array(
            'conversations' => $discussions,
            'message' => $message
        ));
    }

    public function convers()
    {
        $id = (int)$this->getParams()[0];
        $convers = $this->conversationModel->get($id);

        if (empty($convers)) {
            throw new ConversationNotFoundException($id);
        }

        $file = $this->conversationConfig->getPath() . $id . '.xml';

        if (!file_exists($file)) {
            throw new FileNotFoundException($file);
        }

        $conversation = new \SimpleXMLElement(file_get_contents($file));

        $this->getView()->render('conversations/convers', array(
            'conversation' => $conversation
        ));
    }

    public function create()
    {

    }
}