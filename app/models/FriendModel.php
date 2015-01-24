<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 24/01/15
 * Time: 20:28
 */

namespace app\models;


use SwagFramework\Database\DatabaseProvider;
use SwagFramework\mvc\Model;


class FriendModel extends Model {

    /**
     * Return all friend of the user in param
     * @param $id of the the user
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getAllFriendById($id){

        $sql = 'SELECT user2, user.username FROM user_friend, user WHERE user_friend.user2 = user.id AND user_friend.user1 = ?';

        return DatabaseProvider::connection()->query($sql,[$id]);
    }

    public function insertFriend($params){
        $sql = 'INSERT INTO user_friend VALUES (LEAST(:user1, :user2), GREATEST(:user1, :user2), FALSE)';

        return DatabaseProvider::connection()->execute($sql, $params);
    }

    public function deleteFriend($id1,$id2){

        $sql = 'DELETE FROM user_friend WHERE user1 = ? AND user2 = ?';

        return DatabaseProvider::connection()->execute($sql,[$id1,$id2]);

    }

}