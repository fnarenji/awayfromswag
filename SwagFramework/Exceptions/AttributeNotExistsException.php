<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 16/01/15
 * Time: 19:42
 */

namespace SwagFramework\Exceptions;


class AttributeNotExistsException extends SwagException
{
    public function __construct($name, $code = 0, Exception $previous = null)
    {
        $message = 'The attribute "' . $name . '" not exists.';

        parent::__construct($message, $code, $previous);
    }
}