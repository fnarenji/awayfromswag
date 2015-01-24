<?php
/**
 * Created by PhpStorm.
 * User: sknz
 * Date: 13/01/15
 * Time: 20:43
 */

namespace SwagFramework\Database;

use SwagFramework\Config\DatabaseConfig;
use SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException;

class DatabaseProvider
{
    /**
     * @var \SwagFramework\Database\Database
     */
    static private $database;

    static private $databaseConfigFile;

    private function __construct()
    {
    }

    public static function connection()
    {
        if (!(self::$database instanceof Database)) {
            if (empty(self::$databaseConfigFile)) {
                throw new DatabaseConfigurationNotLoadedException();
            }

            self::$database = new Database(DatabaseConfig::parseFromFile(self::$databaseConfigFile));
        }

        return self::$database;
    }

    public static function connect($databaseConfigFile)
    {
        self::$databaseConfigFile = $databaseConfigFile;
    }

    private function __clone()
    {
    }
}