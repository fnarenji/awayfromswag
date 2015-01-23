<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 08/12/14
 * Time: 11:24
 */

namespace SwagFramework\mvc;

use SwagFramework\Helpers\Authentication;
use SwagFramework\Helpers\ViewHelpers;

class View extends \Twig_Environment
{
    private $helpers;

    public function __construct(\Twig_LoaderInterface $loader = null, $options = [])
    {
        parent::__construct($loader, $options);
        $this->helpers = new ViewHelpers();
    }

    public function loadTemplate($name, $index = null)
    {
        $name = $name . '.twig';
        return parent::loadTemplate($name, $index);
    }

    public function render($name, array $context = [])
    {
        $context = array_merge(['helpers' => $this->helpers], $context);
        $context = Authentication::getInstance()->addToContext($context);

        echo parent::render($name, $context);
    }

    public function redirect($to)
    {
        //header('Location: ' . $to);
        // Quand ça marche pas, wallhack + aimbot = win.
        echo '<script>location.href="' . $to . '"</script>';
    }
}