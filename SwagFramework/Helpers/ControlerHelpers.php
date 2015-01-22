<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 05/01/15
 * Time: 21:59
 */

namespace SwagFramework\Helpers;

class ControlerHelpers
{
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