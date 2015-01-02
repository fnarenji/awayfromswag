<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 08/12/14
 * Time: 11:24
 */

namespace SwagFramework\mvc;


use SwagFramework\Helpers\Helpers;

class View extends \Twig_Environment
{
    public function loadTemplate($name, $index = null)
    {
        $name = $name . '.twig';
        return parent::loadTemplate($name, $index);
    }

    public function render($name, array $context = array())
    {
        $helpers = array(
            'helpers'       =>      new Helpers()
        );
        echo parent::render($name, array_merge($helpers, $context));
    }
} 