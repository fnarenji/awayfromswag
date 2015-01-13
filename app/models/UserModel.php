<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/01/15
 * Time: 10:07
 */

namespace app\models;

use SwagFramework\mvc\Model;

class UserModel extends Model
{
    const TABLE_NAME = 'Users';

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

        return $this->getDatabase()->execute($sql, $id);
    }

    /**
     * Return all information for all users
     * @return array
     */
    public function getAllUsers()
    {
        $sql = 'SELECT *'
            . 'FROM ' . self::TABLE_NAME . ' ';

        return $this->getDatabase()->execute($sql, null);
    }

    /**
     * Return information if the user with the $password and $username was found.
     * @param $username
     * @param $password
     * @return array
     */
    public function getUserConnect($username, $password)
    {
        $sql = 'SELECT idUsers, userName, firstName, lastName '
            . 'FROM ' . self::TABLE_NAME . ' '
            . 'WHERE userName = ?'
            . 'AND password = ?';

        return $this->getDatabase()->execute($sql, $username, sha1($password));
    }

    /**
     * Insert in database a new user
     *
     * @param $username
     * @param $firstName
     * @param $lastName
     * @param $mail
     * @param $password
     * @return bool
     */
    public function insertUser($username, $firstName, $lastName, $mail, $password)
    {

        $nbuser = self::nbUsers();

        $sql = 'INSERT INTO users (`userName`, `firstName`, `lastName`, `mail`, `password`, `position`)
                 VALUES (?,?,?,?,?,?)';

        $this->getDatabase()->execute($sql, $username, $firstName, $lastName, $mail, $password, $nbuser);

        return true;
    }

    /**
     * Return the number of users.
     * @return mixed
     */
    private function nbUsers()
    {

        $sql = 'SELECT COUNT(idUsers) '
            . 'FROM ' . self::TABLE_NAME;

        return $this->getDatabase()->execute($sql, null)[0];
    }

    /**
     *  Delete an user
     * @param $id
     * @return bool
     */
    public function deleteUser($id)
    {

        $sql = 'DELETE FROM `Users` WHERE idUsers = ?';

        $this->getDatabase()->execute($sql, $id);

        return true;

    }

    /**
     * Update the user with id in paramater
     * @param $id
     * @param $username
     * @param $firstName
     * @param $lastName
     * @param $mail
     * @param $password
     * @return bool
     */
    public function updateUser($id, $username, $firstName, $lastName, $mail, $password)
    {
        $sql = 'UPDATE Users SET userName = ?, firstName = ?, lastName = ?, mail = ?, password = ? WHERE idUsers = ?';

        return $this->getDatabase()->update($sql, $username, $firstName, $lastName, $mail, $password, $id);

    }


} 