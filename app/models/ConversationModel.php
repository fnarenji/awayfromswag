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
    const UPDATE_LAST_READ = "UPDATE conversation_user SET lastread = NOW() WHERE id = ? AND user = ?";
    const QUIT_CONVERSATION = 'DELETE FROM conversation_user WHERE id = ? AND user = ?';

    const UPDATE_MESSAGE_POSTED = <<<SQL
UPDATE conversation_user, conversation
SET messagecount = messagecount + 1, lastmessagesnippet = :message, lastmessageauthor = :user
WHERE conversation.id = :conversation
  AND conversation.id = conversation_user.id
  AND conversation_user.user = :user
SQL;

    const GET_CONVERSATIONS_FOR_USER = <<<SQL
SELECT conversation.id, conversation.title, lastmessagesnippet, lastmessagetime, lastread, lastmessagetime > lastread notread
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
JOIN conversation_user ON conversation_user.id = conversation.id
WHERE conversation.id = :conversation
  AND conversation_user.user = :user
SQL;

    const GET_CONVERSATION_SEEN = <<<SQL
SELECT CONCAT(username, ' (', firstname, ' ', lastname, ')') fullname
FROM conversation
JOIN conversation_user ON conversation.id = conversation_user.id
JOIN user ON user.id = conversation_user.user
WHERE conversation.id = ?
  AND lastread > lastmessagetime;
SQL;

    const COUNT_NEW_MESSAGES = <<<SQL
SELECT COUNT(*)
FROM conversation_user
JOIN conversation ON conversation.id = conversation_user.id
WHERE conversation_user.user = ?
  AND lastmessagetime > conversation_user.lastread;
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

    /**
     * Get a conversation
     * @param $conversationId
     * @return array containing all the data pertaining to a conversion (creator, messages, metadata)
     * @throws FileNotFoundException
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getConversation($conversationId)
    {
        DatabaseProvider::connection()->execute(self::UPDATE_LAST_READ, [$conversationId, Authentication::getInstance()->getUserId()]);
        $conversation = DatabaseProvider::connection()->selectFirst(self::GET_CONVERSATION, [
            'conversation' => $conversationId,
            'user' => Authentication::getInstance()->getUserId()
        ]);

        $conversation['seen'] = '';
        $query = DatabaseProvider::connection()->query(self::GET_CONVERSATION_SEEN, [$conversationId]);
        foreach ($query as $seen)
            $conversation['seen'] .= $seen['fullname'] . ', ';
        $conversation['seen'] .= ')';
        $conversation['seen'] = str_replace(', )', '.', $conversation['seen']);

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
    }

    /**
     * Checks if a conversation already exists between the user IDs
     * @param array $userIds The user ids to check for
     * @return int the id of the existing conversation, null if none
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function conversationExistsBetween(array $userIds)
    {
        $userIds = array_unique($userIds);
        $userIdStr = '(';
        foreach ($userIds as $userId)
            $userIdStr .= $userId . ', ';
        $userIdStr .= ')';
        $userIdStr = str_replace(', )', ')', $userIdStr);

        $userIdsSize = sizeof($userIds);

        $sql = <<<SQL
SELECT cu.id
FROM conversation_user cu
WHERE NOT EXISTS (SELECT *
                  FROM conversation_user
                  WHERE cu.id = conversation_user.id
                    AND conversation_user.user NOT IN $userIdStr)
GROUP BY cu.id
HAVING COUNT(*) = $userIdsSize;
SQL;

        $id = DatabaseProvider::connection()->selectFirst($sql);
        return $id ? $id['id'] : null;
    }

    public function removeFromConversation($conversationId)
    {
        return DatabaseProvider::connection()->execute(self::QUIT_CONVERSATION, [$conversationId, Authentication::getInstance()->getUserId()]);
    }

    public function countUnreadMessages()
    {
        return DatabaseProvider::connection()->execute(self::COUNT_NEW_MESSAGES, [Authentication::getInstance()->getUserId()]);
    }
}