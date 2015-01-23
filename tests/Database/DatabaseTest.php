<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 30/12/14
 * Time: 19:24
 */

namespace tests\Database;

use SwagFramework\Config\DatabaseConfig;
use SwagFramework\Database\Database;

class DatabaseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SwagFramework\Database\Database
     */
    private $database;

    private function createDatabase()
    {
        $this->database = new Database(DatabaseConfig::parseFromFile("tests/Database/testdatabase.json"));
        $this->assertNotNull($this->database);
    }

    private function selectTable($table)
    {
        $sql = 'show fields from ' . $table;
        $res = $this->database->execute($sql, null);

        $this->assertNotEmpty($res);
    }

    public function testDatabase()
    {
        $this->createDatabase();
        $this->selectTable('user');
    }
}