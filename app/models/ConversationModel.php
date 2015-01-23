<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 16/01/15
 * Time: 10:16
 */

namespace app\models;


use SwagFramework\Database\DatabaseProvider;
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
    public function insertConversation($idUser)
    {

        try {
            DatabaseProvider::connection()->beginTransaction();

            $sql = "INSERT INTO conversation_user ('id', 'user') VALUES (?,?);";
            $sqlOther = "INSERT INTO   conversation ('createtime') VALUES (?);";

            DatabaseProvider::connection()->query($sqlOther, [new \DateTime()]);

            $tmp = $this->getIdConversation();

            DatabaseProvider::connection()->query($sql, [$tmp, $idUser]);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
        }

        return false;
    }

    /**
     * Return id of last conversation
     * @return mixed
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    private function getIdConversation()
    {
        $sql = "SELECT MAX(id) FROM conversation";

        return DatabaseProvider::connection()->query($sql, [])[0];
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