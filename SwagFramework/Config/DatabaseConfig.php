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
     * @var string
     */
    private $host;
    /**
     * @var string
     */
    private $user;
    /**
     * @var string
     */
    private $password;
    /**
     * @var int
     */
    private $port;
    /**
     * @var string
     */
    private $database;

    /**
     * Disable construction of object.
     * @param $host string the host hosting the database server
     * @param $user string the username which to use
     * @param $password string the password associated with the username
     * @param $database string the database to select upon connecting
     * @param $port int the port
     */
    private function __construct($host, $user, $password, $database, $port)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->port = $port;
    }

    /**
     * @param $fileName string database config file
     * @return DatabaseConfig
     * @throws \SwagFramework\Exceptions\MissingConfigEntryException
     */
    public static function parseFromFile($fileName = 'app/config/database.json')
    {
        $configFile = new ConfigFileParser($fileName);
        return new self($configFile->getEntry("host"),
            $configFile->getEntry("user"),
            $configFile->getEntry("password"),
            $configFile->getEntry("database"),
            $configFile->getEntry("port"));
    }

    /**
     * generate Data source name string (connection string)
     * @return string data source name string
     */
    public function dsn()
    {
        $dsn = 'mysql:';
        $dsn .= 'host=' . $this->host . ':' . $this->port;
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