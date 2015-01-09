<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/01/15
 * Time: 10:07
 */

use \SwagFramework\Database\Database;

class userModel {

    const table = 'Users';

    /**
     *  Return all information with the id
     * @param $id
     * @return array
     */
    public function getUser($id){
        $sql = 'SELECT * '
                .'FROM ' .self::table.' '
                .'WHERE id= ?';

        return Database::execute($sql,$id);
    }

    /**
     * Return all information for all users
     * @return array
     */
    public function getAllUsers(){
        $sql = 'SELECT *'
                . 'FROM ' .self::table. ' ';

        return Database::execute($sql,null);
    }

    /**
     * Return information if the user with the $password and $username was found.
     * @param $username
     * @param $password
     * @return array
     */
    public function getUserConnect($username,$password){
        $sql = 'SELECT idUsers, userName, firstName, lastName '
                . 'FROM ' .self::table. ' '
                . 'WHERE userName = ?'
                . 'AND password = ?';

        return Database::execute($sql,$username,sha1($password));
    }

    /**
     * Return the number of users.
     * @return mixed
     */
    private function nbUsers(){

        $sql = 'SELECT COUNT(idUsers) '
                . 'FROM ' .self::table;

        return Database::execute($sql,null)[0];
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
    public function insertUser($username, $firstName, $lastName, $mail, $password){

        $nbuser = self::nbUsers();

        $sql = 'INSERT INTO users (`userName`, `firstName`, `lastName`, `mail`, `password`, `position`)
                 VALUES (?,?,?,?,?,?)';

        Database::execute($sql,$username,$firstName,$lastName,$mail,$password,$nbuser);

        return true;
    }

    /**
     *  Delete an user
     * @param $id
     * @return bool
     */
    public function deleteUser($id){

        $sql = 'DELETE FROM `Users` WHERE idUsers = ?';

        Database::execute($sql,$id);

        return true;

    }


} 