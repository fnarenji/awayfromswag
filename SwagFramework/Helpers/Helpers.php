<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 02/01/15
 * Time: 17:47
 */

namespace SwagFramework\Helpers;


class Helpers {

    public $assets;
    public $form;

    function __construct()
    {
        $this->assets = new Assets();
        $this->form = new Form();
    }


}