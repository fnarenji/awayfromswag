<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 08/12/14
 * Time: 11:24
 */

namespace SwagFramework\mvc;

use SwagFramework\Helpers\ViewHelpers;

class View extends \Twig_Environment
{
    private $helpers;

    public function __construct(\Twig_LoaderInterface $loader = null, $options = array())
    {
        parent::__construct($loader, $options);
        $this->helpers = new ViewHelpers();
    }

    public function loadTemplate($name, $index = null)
    {
        $name = $name . '.twig';
        return parent::loadTemplate($name, $index);
    }

    public function render($name, array $context = array())
    {
        echo parent::render($name, array_merge(array(
            'helpers' => $this->helpers
        ), $context));
    }
} 