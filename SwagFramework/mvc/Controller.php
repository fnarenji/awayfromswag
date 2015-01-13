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
use SwagFramework\Exceptions\InvalidModelClassException;
use SwagFramework\Helpers\ControllerHelpers;

class Controller
{
    public $helpers;
    private $loader;
    private $view;
    private $params;
    private $databaseConfig;

    function __construct()
    {
        $this->loader = new \Twig_Loader_Filesystem('app/views');
        $this->view = new View($this->loader);
        $this->helpers = new ControllerHelpers();
    }

    public function setDatabaseConfig(DatabaseConfig $databaseConfig)
    {
        $this->databaseConfig = $databaseConfig;
    }

    /**
     * @param $model
     */
    public function loadModel($model)
    {
        $classInfo = new \ReflectionClass($model);
        if (!$classInfo->getParentClass()->isSubclassOf('Model')) {
            throw new InvalidModelClassException($model);
        }
        return $classInfo->newInstanceArgs([new Database($this->databaseConfig)]);
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return \SwagFramework\mvc\View
     */
    public function getView()
    {
        return $this->view;
    }
} 