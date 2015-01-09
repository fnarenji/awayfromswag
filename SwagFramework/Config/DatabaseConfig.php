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
     * @var instance
     */
    private static $instance;

    /**
     * @var host
     */
    private $host = 'localhost';

    /**
     * @var user
     */
    private $user = 'root';

    /**
     * @var password
     */
    private $password = '';

    /**
     * @var database
     */
    private $database = 'afs';


    /**
     * Disable construction of object.
     */
    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!(self::$instance instanceof self))
            self::$instance = new self();

        return self::$instance;
    }

    /**
     * generate Data source name string (connection string)
     * @return string data source name string
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
     * @return string user name for mysql connection
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * get password
     * @return string password for mysql connection for the user returned by getUser()
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Disable copy of object
     */
    private function __clone()
    {
    }
}