<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 16/01/15
 * Time: 19:35
 */

namespace SwagFramework\Exceptions;

use Exception;

class MissingParamsException extends SwagException
{
    public function __construct($code = 0, Exception $previous = null)
    {
        $message = 'Missing parameters for this page.';

        parent::__construct($message, $code, $previous);
    }
}