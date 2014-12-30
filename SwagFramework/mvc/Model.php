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
    private $db;

    /**
     * default constructor
     */
    function __construct()
    {
        $this->db = new Database(new DatabaseConfig());
    }
} 