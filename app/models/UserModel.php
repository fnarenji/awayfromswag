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
    const TABLE_NAME = 'user';

    /**
     *  Return all information with the id
     * @param $id
     * @return array
     */
    public function getUser($id)
    {
        $sql = 'SELECT * '
            . 'FROM ' . self::TABLE_NAME . ' '
            . 'WHERE id = ?';

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
            . 'FROM ' . self::TABLE_NAME . ' '
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
            . 'FROM ' . self::TABLE_NAME . ' ';

        return DatabaseProvider::connection()->query($sql, []);
    }

    /**
     * Return information if the user with the $password and $username was found.
     * @param $username
     * @param $password
     * @return boolean id of the user if valid auth or null otherwise
     */
    public function validateAuthentication($username, $password)
    {
        $sql = 'SELECT id '
            . 'FROM ' . self::TABLE_NAME . ' '
            . 'WHERE username = ? '
            . 'AND password = SHA1(?)';

        return DatabaseProvider::connection()->selectFirst($sql, [$username, $password])[0];
    }

    /**
     * Insert in database a new user
     * @param $infos
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function insertUser($infos)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();

            $sql = "INTO INTO " . self::TABLE_NAME . " ('username', 'firstname', 'lastname', 'mail', 'password', 'birthday','phonenumber', .
                'twitter','skype','facebookuri','website','job','description','privacy','mailnotifications','accesslevel')
                     VALUES ( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? )";

            DatabaseProvider::connection()->query($sql, $infos);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {

            DatabaseProvider::connection()->rollBack();
        }
        return false;
    }

    /**
     *  Delete an user
     * @param $id
     * @return bool
     */
    public function deleteUser($id)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();

            $sql = 'DELETE FROM ' . self::TABLE_NAME . ' WHERE id = ?';

            DatabaseProvider::connection()->query($sql, [$id]);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {

            DatabaseProvider::connection()->rollBack();
        }

        return false;
    }

    /**
     * Update the user.
     * @param $infos
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateUser($infos)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();

            $sql = 'UPDATE ' . self::TABLE_NAME . " SET 'firstname' = ?, 'lastname' = ?, 'mail' = ?, 'password' = ?, 'birthday' = ?,
                    'phonenumber' = ?,'twitter' = ?, 'skype' = ?, 'facebookuri' = ?, 'website' = ?, 'job' = ?, 'description' = ?,
                    'privacy' = ?, 'mailnotifications' = ?, 'accesslevel' = ? WHERE id = ? ";

            DatabaseProvider::connection()->update($sql, $infos);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {

            DatabaseProvider::connection()->rollBack();
        }

        return false;
    }

    /**
     * Update user by admin
     * @param $infos
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateAdminUser($infos)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = 'UPDATE ' . self::TABLE_NAME . ' SET username = :username, firstname = :firstname, lastname = :lastname, mail = :mail, password = :password, birthday = :birthday,
                    phonenumber = :phonenumber,twitter = :twitter, skype = :skype, facebookuri = :facebookuri, website = :website, job = :job, description = :description,
                    privacy = :privacy, mailnotifications = :mailnotifications, accesslevel = :accesslevel WHERE id = ?';

            DatabaseProvider::connection()->update($sql, $infos);
            DatabaseProvider::connection()->commit();
            return true;

        } catch (\Exception $e) {

            DatabaseProvider::connection()->rollBack();
        }

        return false;
    }


    /**
     * Return data for username like param
     * @param $name
     * @return array
     */
    public function getUserLike($name)
    {

        $name = '%' . $name . '%';

        $sql = 'SELECT id, username, firstname, lastname FROM ' . self::TABLE_NAME . ' WHERE username LIKE ? ';

        return DatabaseProvider::connection()->query($sql, [$name]);
    }


} 