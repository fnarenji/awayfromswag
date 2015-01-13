<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 27/12/14
 * Time: 23:19
 */

namespace SwagFramework\Exceptions;


class InputNotSetException extends SwagException
{
    public function __construct($input, $key, $code = 0, Exception $previous = null)
    {
        $message = 'Var ' . $input . '[' . $key . ']' . ' not set !';

        parent::__construct($message, $code, $previous);
    }
}