<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 30/12/14
 * Time: 00:46
 */

namespace SwagFramework\Exceptions;


class XMLArrayBadFormatException extends SwagException
{
    public function __construct($input, $key, $code = 0, Exception $previous = null)
    {
        $message = 'The array provided for the tag creation doesn\'t match with the require format';
        $message .= 'Require format : array("title" => "tag title", "content" => "tag content",';
        $message .= '["options" => "options for the tag"]';


        parent::__construct($message, $code);

        if (!is_null($previous)) {
            $this->previous = $previous;
        }
    }
}