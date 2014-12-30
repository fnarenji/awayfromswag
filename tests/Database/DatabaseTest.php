<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 30/12/14
 * Time: 19:24
 */

namespace tests\Database;


use SwagFramework\Config\DatabaseConfig;
use \SwagFramework\Database\Database;

class DatabaseTest extends \PHPUnit_Framework_TestCase {
    private $database;

    private function createDatabase() {
        $this->database = new Database(new DatabaseConfig());
    }

    public function testDatabase() {
        $this->createDatabase();
        $this->assertNotNull($this->database);
    }
}