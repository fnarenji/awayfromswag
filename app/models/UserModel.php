<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/01/15
 * Time: 10:07
 */

namespace app\models;

use SwagFramework\Database\DatabaseProvider;
use SwagFramework\mvc\Model;

class UserModel extends Model
{
    const GET_USER_FULL_NAME_LIKE = 'SELECT id FROM user WHERE CONCAT(username, \' (\', firstname, \' \', UPPER(lastname), \')\') LIKE ?';

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
    public function getAllUsers()
    {
        $sql = 'SELECT *'
            . 'FROM user ';

        return DatabaseProvider::connection()->query($sql);
    }

    /**
     * Return all information for all users
     * @return array
     */
    public function getAllUsersFullNames()
    {
        $sql = 'SELECT CONCAT(username, \' (\', firstname, \' \', UPPER(lastname), \')\') AS userFullName FROM user';

        $fullNames = [];
        foreach (DatabaseProvider::connection()->query($sql) as $row) {
            $fullNames[] = $row['userFullName'];
        }

        return $fullNames;
    }

    /**
     * Return information if the user with the $password and $username was found.
     * @param $username
     * @param $password
     * @return boolean id of the user if valid auth or false otherwise
     */
    public function validateAuthentication($username, $password)
    {
        $sql = 'SELECT id, MD5(mail) as mailHash '
            . 'FROM user '
            . 'WHERE username = ? '
            . 'AND password = SHA1(?)';

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

            $sql = <<<SQL
INSERT INTO user (username, firstname, lastname, mail, password, birthday,phonenumber, twitter, skype, facebookuri, website, job, description, privacy, mailnotifications, accesslevel)
        VALUES (:username, :firstname, :lastname, :mail, SHA1(:password), :birthday, :phonenumber, :twitter, :skype, :facebookuri, :website, :job, :description, :privacy, :mailnotifications, :accesslevel)
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

            $sql = 'UPDATE user '
                . 'SET lastname=:lastname, firstname=:firstname, job=:job, description=:description, mail=:mail, phonenumber=:phonenumber, twitter=:twitter, facebookuri=:facebookuri, skype=:skype, website=:website, privacy=:privacy '
                . 'WHERE id=:id;';
            $state = DatabaseProvider::connection()->execute($sql, $infos);

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
            $sql = 'UPDATE user SET username = :username, firstname = :firstname, lastname = :lastname, mail = :mail, password = :password, birthday = :birthday,
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
     * @param $username
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getAccessLevel($username)
    {
        $sql = 'SELECT accesslevel FROM user WHERE username = ?';

        return DatabaseProvider::connection()->query($sql, array($username));
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

        return DatabaseProvider::connection()->execute(self::GET_USER_FULL_NAME_LIKE, [$fullName]);
    }
} 