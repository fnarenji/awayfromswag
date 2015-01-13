<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 08/12/14
 * Time: 11:24
 */

namespace SwagFramework\mvc;

use SwagFramework\Config\DatabaseConfig;
use SwagFramework\Database\Database;

class Model
{
    /**
     * @var database
     */
    private $database;

    /**
     * default constructor
     * @param DatabaseConfig $config
     */
    public function __construct(Database $db)
    {
        $this->database = $db;
    }

    public function getDatabase()
    {
        return $this->database;
    }
} 