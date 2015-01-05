<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 08/12/14
 * Time: 11:24
 */

namespace SwagFramework\mvc;


use SwagFramework\Helpers\Assets;
use SwagFramework\Helpers\Form;

class View extends \Twig_Environment
{
    public function loadTemplate($name, $index = null)
    {
        $name = $name . '.twig';
        return parent::loadTemplate($name, $index);
    }

    public function render($name, array $context = array())
    {
        $helpers = new \stdClass();
        $helpers->assets = new Assets();
        $helpers->form = new Form();

        echo parent::render($name, array_merge(array(
            'helpers'       =>      $helpers
        ), $context));
    }
} 