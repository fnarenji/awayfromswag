<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 16/01/15
 * Time: 10:16
 */

namespace app\models;


use DOMDocument;
use SebastianBergmann\Exporter\Exception;
use SwagFramework\Config\ConfigFileParser;
use SwagFramework\Database\DatabaseProvider;
use SwagFramework\Exceptions\FileNotFoundException;
use SwagFramework\Helpers\Authentication;
use SwagFramework\mvc\Model;

class ConversationModel extends Model
{
    const ADD_USER_TO_CONVERSATION = "REPLACE INTO conversation_user (id, user) VALUES (?, ?);";
    const DELETE_CONVERSATION = "DELETE FROM conversation_user WHERE id = ?";
    const CREATE_CONVERSATION = "INSERT INTO conversation (user, title) VALUES (?, ?);";
    const GET_ALL_CONVERSATIONS = "SELECT conversation_user.id, username FROM conversation_user,user WHERE user.id = conversation_user.user";

    const UPDATE_MESSAGE_POSTED = <<<SQL
UPDATE conversation_user, conversation
SET messagecount = messagecount + 1, lastmessagesnippet = :message
WHERE conversation.id = :conversation
  AND conversation.id = conversation_user.id
  AND conversation_user.user = :user
SQL;

    const GET_CONVERSATIONS_FOR_USER = <<<SQL
SELECT conversation.id, conversation.title, lastmessagesnippet, lastmessagetime
FROM conversation_user
JOIN user ON conversation_user.user = user.id
JOIN conversation ON conversation_user.id = conversation.id
WHERE conversation_user.user = ?
ORDER BY lastmessagetime DESC
SQL;
    const GET_CONVERSATION = <<<'SQL'
SELECT conversation.id, conversation.title,
  CONCAT(creator.username, ' (', creator.firstname, ' ', creator.lastname, ')') creator,
  DATE_FORMAT(createtime, '%d/%m/%Y %H:%i:%s') as createtime,
  DATE_FORMAT(lastmessagetime, '%d/%m/%Y %H:%i:%s') as lastmessagetime,
  CONCAT(lastmessageauthor.username, ' (', lastmessageauthor.firstname, ' ', lastmessageauthor.lastname, ')') lastmessageauthor
FROM conversation
JOIN user creator ON conversation.user = creator.id
JOIN user lastmessageauthor ON conversation.lastmessageauthor = lastmessageauthor.id
JOIN conversation_user ON conversation_user.user = :user
WHERE conversation.id = :conversation
SQL;
    private $conversationFolder;

    public function __construct()
    {
        $config = new ConfigFileParser(FSROOT . '/app/config/conversation.json');
        $this->conversationFolder = FSROOT . $config->getEntry("path");
    }

    /**
     * Get all conversation
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getAll()
    {
        return DatabaseProvider::connection()->query(self::GET_ALL_CONVERSATIONS);
    }

    /**
     * Get conversation by user id.
     * @param $id
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getForUser($id)
    {
        return DatabaseProvider::connection()->query(self::GET_CONVERSATIONS_FOR_USER, [$id]);
    }

    /**
     * Insert a new conversation
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function createConversation($title)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();
            DatabaseProvider::connection()->execute(self::CREATE_CONVERSATION, [Authentication::getInstance()->getUserId(), $title]);
            $conversationId = DatabaseProvider::connection()->lastInsertId();

            $doc = new \DOMDocument();
            $conversation = $doc->createElement("conversation");
            $conversationIdNode = $doc->createElement("id", $conversationId);
            $conversation->appendChild($conversationIdNode);

            $doc->appendChild($conversation);

            $doc->save($this->conversationFolder . $conversationId . '.xml');
            DatabaseProvider::connection()->commit();
            return $conversationId;
        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * Delete a conversation
     * @param $id
     * @return bool
     */
    public function deleteConversation($id)
    {
        return DatabaseProvider::connection()->execute(self::DELETE_CONVERSATION, [$id]);
    }

    /**
     * Adds a user to a conversation
     * @param $id
     * @param $userId
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function addUserToConversation($id, $userId)
    {
        return DatabaseProvider::connection()->execute(self::ADD_USER_TO_CONVERSATION, [$id, $userId]);
    }

    /**
     * Posts a new message to the conversation
     * @param $conversationId int the id of the conversation
     * @param $message string the content of the message
     */
    public function newMessage($conversationId, $message)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();
            DatabaseProvider::connection()->execute(self::UPDATE_MESSAGE_POSTED, [
                'message' => $message,
                'conversation' => $conversationId,
                'user' => Authentication::getInstance()->getUserId()
            ]);

            $userModel = new UserModel();
            $doc = new \DOMDocument();
            $doc->load($this->conversationFolder . $conversationId . '.xml');

            $messageNode = $doc->createElement('message');

            $messageNode->appendChild($doc->createElement('author', Authentication::getInstance()->getUserId()));
            $messageNode->appendChild($doc->createElement('authorName', $userModel->getUserFullName(Authentication::getInstance()->getUserId())));
            $messageNode->appendChild($doc->createElement('date', date('c')));

            $contentNode = $doc->createElement('content');
            $contentNode->appendChild($doc->createCDATASection($message));

            $messageNode->appendChild($contentNode);

            $doc->firstChild->appendChild($messageNode);

            $doc->save($this->conversationFolder . $conversationId . '.xml');

            DatabaseProvider::connection()->commit();
        } catch (Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    public function getConversation($conversationId)
    {
        try {
            $conversation = DatabaseProvider::connection()->selectFirst(self::GET_CONVERSATION, [
                'conversation' => $conversationId,
                'user' => Authentication::getInstance()->getUserId()
            ]);

            var_dump($conversation);

            $conversation['messages'] = [];

            if (!file_exists($this->conversationFolder . $conversationId . '.xml')) {
                throw new FileNotFoundException($this->conversationFolder . $conversationId . '.xml');
            }

            $doc = new \DOMDocument();
            $doc->load($this->conversationFolder . $conversationId . '.xml');

            $xpath = new \DOMXPath($doc);

            foreach ($xpath->query('/conversation/message') as $messageNode) {
                $message = [];

                foreach ($messageNode->childNodes as $node)
                    $message[$node->nodeName] = $node->nodeValue;

                $message['date'] = (new \DateTime($message['date']))->format('d/m/Y H:i:s');
                $conversation['messages'][] = $message;
            }

            return $conversation;
        } catch (Exception $e) {

        }
    }
}