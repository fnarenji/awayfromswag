<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 27/12/14
 * Time: 23:19
 */

namespace SwagFramework\Exceptions;


class FileNotFoundException extends SwagException
{
    public function __construct($file, $code = 0, Exception $previous = null)
    {
        $message = 'The file ' . $file . ' was not found !';
        parent::__construct($message, $code, $previous);
    }
}