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
    static private $database;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function connection()
    {
        if (!(self::$database instanceof Database)) {
            throw new DatabaseConfigurationNotLoadedException();
        }

        return self::$database;
    }

    public static function connect(DatabaseConfig $databaseConfig)
    {
        self::$database = new Database($databaseConfig);
    }
}