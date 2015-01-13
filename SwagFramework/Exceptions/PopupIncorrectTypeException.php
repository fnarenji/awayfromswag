<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 27/12/14
 * Time: 23:19
 */

namespace SwagFramework\Exceptions;


class PopupIncorrectTypeException extends SwagException
{
    public function __construct($type, $title, $code = 0, Exception $previous = null)
    {
        $message = 'The type ' . $type . ' for the popup ' . $title . ' is incorrect !';

        parent::__construct($message, $code, $previous);
    }
}