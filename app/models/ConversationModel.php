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

    /**
     * Get all conversation
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getAll()
    {
        $sql = "SELECT id, username FROM conversation_user,user WHERE user.id = conversation_user.user";

        return DatabaseProvider::connection()->query($sql, []);
    }

    /**
     * Get conversation by user id.
     * @param $id
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getUser($id)
    {
        $sql = "SELECT conversation_user.id, username FROM conversation_user, user WHERE user.id = conversation_user.user AND conversation_user.user = ? ";

        return DatabaseProvider::connection()->query($sql, [$id]);
    }

    /**
     * Get conversation.
     * @param $id
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function get($id)
    {
        $sql = "SELECT id, username FROM conversation_user,user WHERE user.id = conversation_user.user AND conversation_user.id = ? ";

        return DatabaseProvider::connection()->query($sql, [$id]);
    }

    /**
     * Insert a new conversation
     * @param $idUser
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function insertConversation()
    {

        try {
            DatabaseProvider::connection()->beginTransaction();

            $sql = "INSERT INTO conversation_user ('id', 'user') VALUES (?,?);";
            $sqlOther = "INSERT INTO conversation ('user') VALUES (?);";

            DatabaseProvider::connection()->query($sqlOther, Authentication::getInstance()->getUserId());

            $newConversationId = DatabaseProvider::connection()->lastInsertId();

            DatabaseProvider::connection()->query($sql, [$newConversationId, Authentication::getInstance()->getUserId()]);

            DatabaseProvider::connection()->commit();

            return true;
        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
        }

        return false;
    }

    /**
     * Delete a conversation
     * @param $id
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function deleteConversation($id)
    {

        try {
            DatabaseProvider::connection()->beginTransaction();

            $sql = "DELETE FROM conversation_user WHERE id = ?";
            DatabaseProvider::connection()->query($sql, [$id]);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
        }

        return false;
    }


}