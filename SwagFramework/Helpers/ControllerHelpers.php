<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 05/01/15
 * Time: 21:59
 */

namespace SwagFramework\Helpers;

class ControllerHelpers
{
    /**
     * @var \SwagFramework\Helpers\Popup
     */
    public $popup;

    function __construct()
    {
        $this->popup = new Popup();
    }
}