<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 30/12/14
 * Time: 00:37
 */

namespace SwagFramework\Exceptions;


class XMLNotWritableException extends SwagException
{
    public function __construct($input, $key, $code = 0, Exception $previous = null)
    {
        $message = 'The array provided for the XML tag is ';
        $message .= (count($input) < 1) ? 'too short' : 'too big. ';
        $message .= 'Size expected : 2.';

        parent::__construct($message, $code);

        if (!is_null($previous)) {
            $this->previous = $previous;
        }
    }
}