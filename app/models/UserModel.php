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
use SwagFramework\mvc\Model;

class UserModel extends Model
{
    const SALT = 'LEPHPCESTLOLMDRswagyolo564654465465464//@Êø@Â@ÛÂøîôâåÊøîÂÊÔÎÊÂåÊâÎ';
    const GET_USER_FULL_NAME_LIKE = 'SELECT id FROM user WHERE CONCAT(username, \' (\', firstname, \' \', UPPER(lastname), \')\') LIKE ?';

    const GET_ALL_USER_FULL_NAME = 'SELECT CONCAT(username, \' (\', firstname, \' \', UPPER(lastname), \')\') AS userFullName FROM user';
    const GET_USER_FULL_NAME = 'SELECT CONCAT(username, \' (\', firstname, \' \', UPPER(lastname), \')\') AS userFullName FROM user WHERE id = ?';

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
    public function getUserByName($name)
    {
        $sql = 'SELECT * '
            . 'FROM user '
            . 'WHERE username = ? ';

        return DatabaseProvider::connection()->selectFirst($sql, [$name]);
    }

    /**
     * Return all information for all users
     * @return array
     */
    public function getAllUsers($start = 0, $end = 10)
    {
        $end += $start;
        $sql = <<<SQL
SELECT *, MD5(mail) mailhash, DATE_FORMAT(registerdate, '%d/%m/%Y') registerdate
FROM user
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
        $salt = self::SALT;

        $sql = <<<SQL
SELECT id, firstname, lastname, MD5(mail) mailHash, accesslevel
FROM user
WHERE username = ?
  AND password = SHA1(CONCAT(?, '$salt'))
SQL;

        return DatabaseProvider::connection()->selectFirst($sql, [$username, $password]);
    }

    /**
     * Insert in database a new user
     * @param $infos array each field name with its value
     * @return bool return success
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function insertUser(array $infos)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();

            $salt = self::SALT;

            $sql = <<<SQL
INSERT INTO user (username, firstname, lastname, mail, password, birthday,phonenumber, twitter, skype, facebookuri, website, job, description, privacy, mailnotifications, accesslevel)
        VALUES (:username, :firstname, :lastname, :mail, SHA1(CONCAT(:password, '$salt')), :birthday, :phonenumber, :twitter, :skype, :facebookuri, :website, :job, :description, :privacy, :mailnotifications, :accesslevel)
SQL;

            $success = DatabaseProvider::connection()->execute($sql, $infos);
            DatabaseProvider::connection()->commit();

            return $success;

        } catch (\PDOException $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function deleteUser($id)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();

            $sql = 'DELETE FROM user WHERE username = ?';

            $state = DatabaseProvider::connection()->execute($sql, [$id]);

            DatabaseProvider::connection()->commit();

            return $state;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param $infos
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateUser($infos)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();

            $str = '';

            foreach($infos as $key=>$value){
                if($key != 'id'){
                    $str .= ''.$key.' = \''.$value.'\' ,';
                }
            }

            $str  = substr($str, 0, -1);

            $sql = 'UPDATE user '
                . 'SET '.$str.' WHERE id= ?;';
            $state = DatabaseProvider::connection()->execute($sql, [$infos['id']]);

            DatabaseProvider::connection()->commit();

            return $state;


        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param $infos
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateAdminUser($infos)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = 'UPDATE user SET username = :username, firstname = :firstname, lastname = :lastname, mail = :mail, password = SHA1(CONCAT(:password, \'' . self::SALT . '\')), birthday = :birthday,
                    phonenumber = :phonenumber,twitter = :twitter, skype = :skype, facebookuri = :facebookuri, website = :website, job = :job, description = :description,
                    privacy = :privacy, mailnotifications = :mailnotifications, accesslevel = :accesslevel WHERE id = ?';

            $state = DatabaseProvider::connection()->execute($sql, $infos);
            DatabaseProvider::connection()->commit();
            return $state;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * Return data for username like param
     * @param $name
     * @return array
     */
    public function getUserLike($name)
    {

        $name = '%' . $name . '%';

        $sql = 'SELECT id, username, firstname, lastname FROM user WHERE username LIKE ? ';

        return DatabaseProvider::connection()->query($sql, [$name]);
    }

    public function getUserFullNameLike($fullName)
    {
        $fullName = '%' . $fullName . '%';

        return DatabaseProvider::connection()->selectFirst(self::GET_USER_FULL_NAME_LIKE, [$fullName]);
    }

    public function addToFriend($id)
    {
        $sql = 'INSERT INTO user_friend VALUES (?, ?, 0)';

        $user2 = Authentication::getInstance()->getUserId();

        return DatabaseProvider::connection()->execute($sql, array($user2, $id));
    }

    public function getAllFriends()
    {
        $sql = 'SELECT user1, user2 FROM user_friend WHERE user1 = ? OR user2 = ?';

        $user = Authentication::getInstance()->getUserId();

        return DatabaseProvider::connection()->query($sql, array($user, $user));
    }

    public function count()
    {
        $sql = <<<SQL
SELECT COUNT(id) as nb FROM user;
SQL;

        return DatabaseProvider::connection()->selectFirst($sql, []);
    }
} 