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

    public function testDatabase()
    {
        $this->createDatabase();
        $this->selectTable('user');
        $this->selectUser(1);
    }

    private function createDatabase()
    {
        $this->database = new Database(DatabaseConfig::parseFromFile("tests/Database/testdatabase.json"));
        $this->assertNotNull($this->database);
    }

    private function selectTable($table)
    {
        $sql = 'SHOW FIELDS FROM ' . $table;
        $res = $this->database->query($sql, []);
        $this->assertNotEmpty($res);
    }

    private function selectUser($userID)
    {
        $sql = 'SELECT * FROM user '
            . 'WHERE id=?';
        $res = $this->database->query($sql, [$userID]);
        $this->assertNotEmpty($res);
    }
}