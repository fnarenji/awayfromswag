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

        return DatabaseProvider::connection()->execute($sql, $id);
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

        return DatabaseProvider::connection()->execute($sql, $name);
    }

    /**
     * Return all information for all users
     * @return array
     */
    public function getAllUsers()
    {
        $sql = 'SELECT *'
            . 'FROM ' . self::TABLE_NAME . ' ';

        return DatabaseProvider::connection()->execute($sql, null);
    }

    /**
     * Return information if the user with the $password and $username was found.
     * @param $username
     * @param $password
     * @return boolean true if auth valid false otherwise
     */
    public function validateAuthentication($username, $password)
    {
        $sql = 'SELECT id '
            . 'FROM ' . self::TABLE_NAME . ' '
            . 'WHERE username = ? '
            . 'AND password = ? ';

       return DatabaseProvider::connection()->execute($sql, $username, $password);
    }

    /**
     * Insert in database a new user
     * @param $infos
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function insertUser($infos) {
        try {

            DatabaseProvider::connection()->beginTransaction();

            $sql = "INTO INTO " . self::TABLE_NAME . " ('username', 'firstname', 'lastname', 'mail', 'password', 'birthday','phonenumber', .
                'twitter','skype','facebookuri','website','job','description','privacy','mailnotifications','accesslevel')
                     VALUES ( ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? )";

            DatabaseProvider::connection()->execute($sql, $infos['username'], $infos['firstName'], $infos['lastName'], $infos['mail'], $infos['password'], $infos['birthday'],
                $infos['phonenumber'], $infos['twitter'], $infos['skype'], $infos['facebookuri'], $infos['website'], $infos['job'], $infos['description'],
                $infos['privacy'], $infos['mailnotifications'], $infos['accesslevel']);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {

            DatabaseProvider::connection()->rollBack();
        }
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

            DatabaseProvider::connection()->execute($sql, $id);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {

            DatabaseProvider::connection()->rollBack();
        }

    }

    /**
     * Update the user.
     * @param $infos
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateUser($infos) {
        try {

            DatabaseProvider::connection()->beginTransaction();

            $sql = 'UPDATE ' . self::TABLE_NAME . " SET 'firstname' = ?, 'lastname' = ?, 'mail' = ?, 'password' = ?, 'birthday' = ?,
                    'phonenumber' = ?,'twitter' = ?, 'skype' = ?, 'facebookuri' = ?, 'website' = ?, 'job' = ?, 'description' = ?,
                    'privacy' = ?, 'mailnotifications' = ?, 'accesslevel' = ? WHERE id = ? ";

            DatabaseProvider::connection()->update($sql, $infos['firstName'], $infos['lastName'], $infos['mail'], $infos['password'], $infos['birthday'],
                $infos['phonenumber'], $infos['twitter'], $infos['skype'], $infos['facebookuri'], $infos['website'], $infos['job'], $infos['description'],
                $infos['privacy'], $infos['mailnotifications'], $infos['accesslevel'], $infos['id']);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {

            DatabaseProvider::connection()->rollBack();
        }

    }

    /**
     * Update user by admin
     * @param $infos
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateAdminUser($infos) {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = 'UPDATE ' . self::TABLE_NAME . ' SET username = ?, firstname = ?, lastname = ?, mail = ?, password = ?, birthday = ?,
                    phonenumber = ?,twitter = ?, skype = ?, facebookuri = ?, website = ?, job = ?, description = ?,
                    privacy = ?, mailnotifications = ?, accesslevel = ? WHERE id = ?';

            DatabaseProvider::connection()->update($sql, $infos['username'], $infos['firstName'], $infos['lastName'], $infos['mail'], $infos['password'], $infos['birthday'],
                $infos['phonenumber'], $infos['twitter'], $infos['skype'], $infos['facebookuri'], $infos['website'], $infos['job'], $infos['description'],
                $infos['privacy'], $infos['mailnotifications'], $infos['accesslevel'], $infos['id']);
            DatabaseProvider::connection()->commit();
            return true;

        } catch (\Exception $e) {

            DatabaseProvider::connection()->rollBack();
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

        $sql = 'SELECT id, username, firstname, lastname FROM ' . self::TABLE_NAME . ' WHERE username LIKE ? ';

        return DatabaseProvider::connection()->execute($sql, $name);
    }


} 