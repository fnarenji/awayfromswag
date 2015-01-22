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
            . 'WHERE id= ?';

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
            . 'WHERE username = ?';

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
        $sql = 'SELECT 1'
            . 'FROM ' . self::TABLE_NAME . ' '
            . 'WHERE username = ?'
            . 'AND password = ?';

        return DatabaseProvider::connection()->query($sql, $username, sha1($password))->rowCount() == 1;
    }

    /**
     * Insert in database a new user
     * @param $username
     * @param $firstName
     * @param $lastName
     * @param $mail
     * @param $password
     * @param $birthday
     * @param $phonenumber
     * @param $twitter
     * @param $skype
     * @param $facebookuri
     * @param $website
     * @param $job
     * @param $description
     * @param $privacy
     * @param $mailnotifications
     * @param $accesslevel
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function insertUser(
        $username,
        $firstName,
        $lastName,
        $mail,
        $password,
        $birthday,
        $phonenumber,
        $twitter,
        $skype,
        $facebookuri,
        $website,
        $job,
        $description,
        $privacy,
        $mailnotifications,
        $accesslevel
    ) {
        try {

            DatabaseProvider::connection()->beginTransaction();

            $sql = 'INSERT INTO ' . self::TABLE_NAME . ' (`userName`, `firstname`, `lastname`, `mail`, `password`, `birthday`,`phonenumber`,' .
                '`twitter`,`skype`,`facebookuri`,`website`,`job`,`description`,`privacy`,`mailnotifications`,`accesslevel`);
                     VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)';

            DatabaseProvider::connection()->execute($sql, $username, $firstName, $lastName, $mail, $password, $birthday,
                $phonenumber, $twitter, $skype, $facebookuri, $website, $job, $description,
                $privacy, $mailnotifications, $accesslevel);
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
     * Update the user with id in paramater
     * @param $id
     * @param $firstName
     * @param $lastName
     * @param $mail
     * @param $password
     * @param $birthday
     * @param $phonenumber
     * @param $twitter
     * @param $skype
     * @param $facebookuri
     * @param $website
     * @param $job
     * @param $description
     * @param $privacy
     * @param $mailnotifications
     * @param $accesslevel
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateUser(
        $id,
        $firstName,
        $lastName,
        $mail,
        $password,
        $birthday,
        $phonenumber,
        $twitter,
        $skype,
        $facebookuri,
        $website,
        $job,
        $description,
        $privacy,
        $mailnotifications,
        $accesslevel
    ) {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = 'UPDATE ' . self::TABLE_NAME . ' SET firstname = ?, lastname = ?, mail = ?, password = ?, birthday = ?,
                    phonenumber = ?,twitter = ?, skype = ?, facebookuri = ?, website = ?, job = ?, description = ?,
                    privacy = ?, mailnotifications = ?, accesslevel = ? WHERE id = ?';

            DatabaseProvider::connection()->update($sql, $firstName, $lastName, $mail, $password, $birthday,
                $phonenumber, $twitter, $skype, $facebookuri, $website,
                $job, $description, $privacy, $mailnotifications,
                $accesslevel, $id);
            DatabaseProvider::connection()->commit();
            return true;

        } catch (\Exception $e) {

            DatabaseProvider::connection()->rollBack();
        }

    }

    /**
     * Update user by admin
     * @param $id
     * @param $username
     * @param $firstName
     * @param $lastName
     * @param $mail
     * @param $password
     * @param $birthday
     * @param $phonenumber
     * @param $twitter
     * @param $skype
     * @param $facebookuri
     * @param $website
     * @param $job
     * @param $description
     * @param $privacy
     * @param $mailnotifications
     * @param $accesslevel
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateAdminUser(
        $id,
        $username,
        $firstName,
        $lastName,
        $mail,
        $password,
        $birthday,
        $phonenumber,
        $twitter,
        $skype,
        $facebookuri,
        $website,
        $job,
        $description,
        $privacy,
        $mailnotifications,
        $accesslevel
    ) {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = 'UPDATE ' . self::TABLE_NAME . ' SET username = ?, firstname = ?, lastname = ?, mail = ?, password = ?, birthday = ?,
                    phonenumber = ?,twitter = ?, skype = ?, facebookuri = ?, website = ?, job = ?, description = ?,
                    privacy = ?, mailnotifications = ?, accesslevel = ? WHERE id = ?';

            DatabaseProvider::connection()->update($sql, $username, $firstName, $lastName, $mail, $password, $birthday,
                $phonenumber, $twitter, $skype, $facebookuri, $website,
                $job, $description, $privacy, $mailnotifications,
                $accesslevel, $id);
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