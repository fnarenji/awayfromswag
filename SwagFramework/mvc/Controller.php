<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 08/12/14
 * Time: 11:24
 */

namespace SwagFramework\mvc;


use SwagFramework\Helpers\Input;
use SwagFramework\Helpers\Popup;

class ControllerHelpers {
    /**
     * @var \SwagFramework\Helpers\Input
     */
    public $input;
    /**
     * @var \SwagFramework\Helpers\Popup
     */
    public $popup;

    function __construct()
    {
        $this->input = new Input();
        $this->popup = new Popup();
    }
}

class Controller
{
    private $loader;
    private $view;
    private $params;
    public $helpers;

    function __construct()
    {
        $this->loader = new \Twig_Loader_Filesystem('app/views');
        $this->view = new View($this->loader);
        $this->helpers = new ControllerHelpers();
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