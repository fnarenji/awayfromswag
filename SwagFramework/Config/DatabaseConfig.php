<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 30/12/14
 * Time: 18:01
 */

namespace SwagFramework\Config;


class DatabaseConfig
{
    /**
     * @var Host
     */
    private $host = 'localhost';

    /**
     * @var User
     */
    private $user = 'root';

    /**
     * @var Password
     */
    private $password = '';

    /**
     * @var Database
     */
    private $database = 'afs';

    /**
     * generate DSN
     * @return string dsn
     */
    public function dsn()
    {
        $dsn = 'mysql:';
        $dsn .= 'host=' . $this->host;
        $dsn .= ';';
        $dsn .= 'dbname=' . $this->database;

        return $dsn;
    }

    /**
     * get user
     * @return string user
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * get password
     * @return string password
     */
    public function getPassword()
    {
        return $this->password;
    }


}