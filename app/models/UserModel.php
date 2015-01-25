<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/01/15
 * Time: 10:07
 */

namespace app\models;

use SwagFramework\Database\DatabaseProvider;
use SwagFramework\Helpers\Authentication;
use SwagFramework\Helpers\MailUtil;
use SwagFramework\mvc\Model;

class UserModel extends Model
{
    const SALT = 'LEPHPCESTLOLMDRswagyolo564654465465464//@Êø@Â@ÛÂøîôâåÊøîÂÊÔÎÊÂåÊâÎ';
    const GET_USER_FULL_NAME_LIKE = 'SELECT id FROM user WHERE CONCAT(username, \' (\', firstname, \' \', UPPER(lastname), \')\') LIKE ?';

    const GET_ALL_USER_FULL_NAME = 'SELECT CONCAT(username, \' (\', firstname, \' \', UPPER(lastname), \')\') AS userFullName FROM user';
    const GET_USER_FULL_NAME = 'SELECT CONCAT(username, \' (\', firstname, \' \', UPPER(lastname), \')\') AS userFullName FROM user WHERE id = ?';
    const SEARCH = "SELECT user.* FROM user WHERE MATCH(username, firstname, lastname, description) AGAINST (:query) OR id = :query";
    const SELECT_BY_USERNAME_LIKE = 'SELECT id, username, firstname, lastname FROM user WHERE username LIKE ? ';
    const INSERT_FRIEND = 'INSERT INTO user_friend VALUES (?, ?, ?)';
    const DELETE_BY_USERNAME = 'DELETE FROM user WHERE username = ?';
    const GET_ALL_FRIENDS = 'SELECT user1, user2 FROM user_friend WHERE user1 = :user1 OR user2 = :user2';

    const COUNT = <<<SQL
SELECT COUNT(id) AS nb FROM user;
SQL;

    const UPDATE_ADMIN_USER = 'UPDATE user SET username = :username, firstname = :firstname, lastname = :lastname, mail = :mail, password = SHA1(CONCAT(:password, :salt)), birthday = :birthday,
                phonenumber = :phonenumber,twitter = :twitter, skype = :skype, facebookuri = :facebookuri, website = :website, job = :job, description = :description,
                privacy = :privacy, mailnotifications = :mailnotifications, accesslevel = :accesslevel WHERE id = ?';

    const INSERT_USER = <<<SQL
INSERT INTO user (username, firstname, lastname, mail, password, birthday,phonenumber, twitter, skype, facebookuri, website, job, description, privacy, mailnotifications, accesslevel)
        VALUES (:username, CONCAT(UCASE(LEFT(:username, 1)), SUBSTRING(:username, 2)), UPPER(:lastname), :mail, SHA1(CONCAT(:password, :salt)), STR_TO_DATE(:birthday, '%d/%m/%Y'), :phonenumber, :twitter, :skype, :facebookuri, :website, :job, :description, :privacy, :mailnotifications, :accesslevel)
SQL;

    const VALIDATE_AUTH = <<<SQL
SELECT id, firstname, lastname, MD5(mail) mailHash, accesslevel
FROM user u
WHERE username = :username
  AND password = SHA1(CONCAT(:password, :salt))
  AND NOT EXISTS (SELECT *
                  FROM user_validation
                  WHERE user_validation.id = u.id)
SQL;
    const INSERT_USER_VALIDATION = 'INSERT INTO user_validation VALUES (?, ?)';
    const VALIDATE_USER = 'DELETE FROM user_validation WHERE token = ?';

    /**
     *  Return all information with the id
     * @param $id
     * @return array
     */
    public function getUser($id)
    {
        $sql = 'SELECT * '
            . 'FROM user '
            . 'WHERE id=?';

        return DatabaseProvider::connection()->selectFirst($sql, [$id]);
    }

    /**
     * Returns all the informations about an user with the username
     * @param $name
     * @return array
     */
    public function getUserByUserName($name)
    {
        $sql = 'SELECT * '
            . 'FROM user '
            . 'WHERE username = ? ';

        return DatabaseProvider::connection()->selectFirst($sql, [$name]);
    }

    /**
     * Return all information for all users
     * @param int $start lower bound of list
     * @param int $end upper bound of list
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getAllUsers($start = 0, $end = 10)
    {
        $end += $start;
        $sql = <<<SQL
SELECT *, MD5(mail) mailhash, DATE_FORMAT(registerdate, '%d/%m/%Y') registerdate
FROM user
ORDER BY username
LIMIT $start, $end;
SQL;

        return DatabaseProvider::connection()->query($sql);
    }

    /**
     * Return all information for all users
     * @return array
     */
    public function getAllUsersFullNames()
    {
        $fullNames = [];
        foreach (DatabaseProvider::connection()->query(self::GET_ALL_USER_FULL_NAME) as $row) {
            $fullNames[] = $row['userFullName'];
        }

        return $fullNames;
    }

    /**
     * Return full name for user
     * @param $id int user id
     * @return string the full name
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getUserFullName($id)
    {
        return DatabaseProvider::connection()->selectFirst(self::GET_USER_FULL_NAME, [$id])['userFullName'];
    }


    /**
     * Return information if the user with the $password and $username was found.
     * @param $username
     * @param $password
     * @return boolean id of the user if valid auth or false otherwise
     */
    public function validateAuthentication($username, $password)
    {
        return DatabaseProvider::connection()->selectFirst(self::VALIDATE_AUTH, [
            'username' => $username,
            'password' => $password,
            'salt' => self::SALT
        ]);
    }

    /**
     * @param array $infos
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function insertUser(array $infos)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();
            $infos = array_merge($infos, ['salt' => self::SALT]);
            $success = DatabaseProvider::connection()->execute(self::INSERT_USER, $infos);
            $userId = DatabaseProvider::connection()->lastInsertId();

            $token = str_shuffle(sha1(microtime() + mt_rand()));

            // Est-ce qu'on en parle des URLS hardcodées dégueulasses ?
            $mailContent = <<<TEXT
Bonjour,

Votre inscription sur Away From Security est en attente de validation.
Veuillez cliquer ici afin de valider celle-ci.

Cordialement,
#HCS
TEXT;
            if ($success)
                MailUtil::send($infos['mail'], 'Validation de votre compte AFS', $mailContent);

            $success = $success && DatabaseProvider::connection()->execute(self::INSERT_USER_VALIDATION, [$userId, $token]);

            DatabaseProvider::connection()->commit();

            return $success;
        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    public function validateToken($token)
    {
        return DatabaseProvider::connection()->execute(self::VALIDATE_USER, [$token]);
    }

    public function getLastUser()
    {
        $sql = "SELECT * FROM user ORDER BY id DESC LIMIT 5";

        return DatabaseProvider::connection()->query($sql);
    }
    /**
     * @param $id
     * @return bool
     * @throws \\Exception
     * @throws \\SwagFramework\\Exceptions\\DatabaseConfigurationNotLoadedException
     */
    public function deleteUser($id)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();

            $user = 'DELETE FROM user WHERE username = ?;';
            $article = 'UPDATE article SET user = -1 WHERE user = ?';
            $comment = 'UPDATE comment SET user = -1 WHERE user = ?';
            $conversation = 'UPDATE conversation_user SET user = -1 WHERE user = ?';
            $event = 'UPDATE event SET user = -1 WHERE user = ?';
            $event_user = 'UPDATE event_user SET user = -1 WHERE user = ?';

            $success = DatabaseProvider::connection()->execute($article, [$id]);
            $success = $success && DatabaseProvider::connection()->execute($comment, [$id]);
            $success = $success && DatabaseProvider::connection()->execute($conversation, [$id]);
            $success = $success && DatabaseProvider::connection()->execute($event, [$id]);
            $success = $success && DatabaseProvider::connection()->execute($event_user, [$id]);
            $success = $success && DatabaseProvider::connection()->execute($user, [$id]);

            DatabaseProvider::connection()->commit();

            return $success;
        } catch (Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param $infos
     * @return bool
     * @throws \\Exception
     * @throws \\SwagFramework\\Exceptions\\DatabaseConfigurationNotLoadedException
     */
    public function updateUser($infos)
    {
        $str = '';

        foreach ($infos as $key => $value) {
            if(empty($value)) continue;
            if ($key != 'id') {
                $str .= '' . $key . ' = "' . $value . '" ,';
            }
        }

        $str = substr($str, 0, -1);

        $sql = 'UPDATE user '
            . 'SET ' . $str . ' WHERE id= ?;';

        return DatabaseProvider::connection()->execute($sql, array($infos['id']));
    }

    /**
     * @param $infos
     * @return bool
     * @throws \\Exception
     * @throws \\SwagFramework\\Exceptions\\DatabaseConfigurationNotLoadedException
     */
    public function updateAdminUser($infos)
    {
        $infos = array_merge($infos, ['salt' => self::SALT]);
        return DatabaseProvider::connection()->execute(self::UPDATE_ADMIN_USER, $infos);
    }

    /**
     * Return data for username like param
     * @param $name
     * @return array
     */
    public function getUserLike($name)
    {
        $name = '%' . $name . '%';

        return DatabaseProvider::connection()->query(self::SELECT_BY_USERNAME_LIKE, [$name]);
    }

    public function getUserFullNameLike($fullName)
    {
        $fullName = '%' . $fullName . '%';

        return DatabaseProvider::connection()->selectFirst(self::GET_USER_FULL_NAME_LIKE, [$fullName]);
    }

    public function addToFriend($id)
    {
        $user2 = Authentication::getInstance()->getUserId();

        return DatabaseProvider::connection()->execute(self::INSERT_FRIEND, array($user2, $id));
    }

    public function getAllFriends()
    {
        if (Authentication::getInstance()->isAuthenticated()) {
            return DatabaseProvider::connection()->query(self::GET_ALL_FRIENDS, [
                'user1' => Authentication::getInstance()->getUserId(),
                'user2' => Authentication::getInstance()->getUserId()
            ]);
        }
        return [];
    }

    public function count()
    {
        return DatabaseProvider::connection()->selectFirst(self::COUNT);
    }

    public function search($query)
    {
        return DatabaseProvider::connection()->query(self::SEARCH, ['query' => $query]);
    }
}