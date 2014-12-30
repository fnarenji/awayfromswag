<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 30/12/14
 * Time: 17:53
 */

namespace SwagFramework\Database;

class Database extends \PDO
{
    /**
     * @var  database config
     */
    private $config;

    /**
     * default constructor
     * @param \SwagFramework\Config\DatabaseConfig|DatabaseConfig $config
     */
    public function __construct(\SwagFramework\Config\DatabaseConfig $config)
    {
        $this->config = $config;

        parent::__construct(
            $this->config->dsn(),
            $this->config->getUser(),
            $this->config->getPassword(),
            array(
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            )
        );
    }

    /**
     * execute query
     * @param $query
     * @param $_
     * @return bool
     */
    public function execute($query, $_)
    {
        $params = func_get_args();
        $stmt = $this->prepare(array_shift($params));
        return $stmt->execute($params);
    }

    /**
     * select all from query
     * @param $query query    * @param $_ params
     * @return array
     */
    public function selectAll($query, $_)
    {
        $params = func_get_args();
        $stmt = $this->prepare(array_shift($params));
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * select first from query
     * @param $query query
     * @param $_ params
     * @return mixed
     */
    public function selectFirst($query, $_)
    {
        $params = func_get_args();
        $stmt = $this->prepare(array_shift($params));
        $stmt->execute($params);
        return $stmt->fetch();
    }
}