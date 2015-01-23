<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 30/12/14
 * Time: 17:53
 */

namespace SwagFramework\Database;

use SwagFramework\Config\DatabaseConfig;

class Database extends \PDO
{
    /**
     * @var  database config
     */
    private $config;

    /**
     * default constructor
     * @param DatabaseConfig|DatabaseConfig $config
     */
    public function __construct(DatabaseConfig $config)
    {
        $this->config = $config;

        parent::__construct(
            $this->config->dsn(),
            $this->config->getUser(),
            $this->config->getPassword(),
            array(
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'
            )
        );
    }

    /**
     * execute query
     * @param $query
     * @param $_
     * @return array
     */
    public function execute($query, $_)
    {
        $params = func_get_args();
        $stmt = $this->prepare(array_shift($params));
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * update query
     * @param $query
     * @param $_
     * @return bool
     */
    public function update($query, $_)
    {
        $params = func_get_args();
        array_shift($params);
        $stmt = $this->prepare($query);
        return $stmt->execute($params);
    }

    /**
     * select first from query
     * @param $query string query
     * @param $_ array params
     * @return mixed
     */
    public function selectFirst($query, $_)
    {
        $params = func_get_args();
        array_shift($params);
        $stmt = $this->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch();
    }
}