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
    private $previous;

    public function __construct($input, $key, $code = 0, Exception $previous = null)
    {
        $message = 'Var ' . $input . '[' . $key . ']' . ' not set !';

        parent::__construct($message, $code);

        if (!is_null($previous)) {
            $this->previous = $previous;
        }
    }
}