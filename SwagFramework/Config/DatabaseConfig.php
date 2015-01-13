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
     * @var host
     */
    private $host;
    /**
     * @var user
     */
    private $user;
    /**
     * @var password
     */
    private $password;
    /**
     * @var database
     */
    private $database;

    /**
     * Disable construction of object.
     */
    private function __construct($host, $user, $password, $database)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
    }

    /**
     * @param $fileName database config file
     */
    public static function parseFromFile($fileName = 'config/database.json')
    {
        $configFile = new ConfigFileParser($fileName);
        return new self($configFile->getEntry("host"),
            $configFile->getEntry("user"),
            $configFile->getEntry("password"),
            $configFile->getEntry("database"));
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
}