<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 16/01/15
 * Time: 10:16
 */

namespace app\models;


use SwagFramework\Database\DatabaseProvider;
use SwagFramework\Helpers\Authentication;
use SwagFramework\mvc\Model;

class ConversationModel extends Model
{
    const ADD_USER_TO_CONVERSATION = "REPLACE INTO conversation_user (id, user) VALUES (?, ?);";
    const DELETE_CONVERSATION = "DELETE FROM conversation_user WHERE id = ?";
    const CREATE_CONVERSATION = "INSERT INTO conversation (user) VALUES (?);";
    const GET_CONVERSATION = "SELECT conversation_user.id, username FROM conversation_user, user WHERE user.id = conversation_user.user AND conversation_user.id = ? ";
    const GET_CONVERSATIONS_FOR_USER = "SELECT conversation_user.id, conversation.title, username FROM conversation_user, user, conversation WHERE conversation_user.id = conversation.id AND user.id = conversation_user.user AND conversation_user.user = ? ";
    const GET_ALL_CONVERSATIONS = "SELECT conversation_user.id, username FROM conversation_user,user WHERE user.id = conversation_user.user";

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
     * Get conversation.
     * @param $id
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function get($id)
    {
        return DatabaseProvider::connection()->query(self::GET_CONVERSATION, [$id]);
    }

    /**
     * Insert a new conversation
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function createConversation()
    {
        DatabaseProvider::connection()->execute(self::CREATE_CONVERSATION, [Authentication::getInstance()->getUserId()]);
        return DatabaseProvider::connection()->lastInsertId();
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
}