<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 27/12/14
 * Time: 23:19
 */

namespace app\exceptions;


use Exception;
use SwagFramework\Exceptions\SwagException;

class NotAuthenticatedException extends SwagException
{
    private $previous;

    public function __construct($code = 0, Exception $previous = null)
    {
        $message = 'You are not authenticated.';

        parent::__construct($message, $code);

        if (!is_null($previous)) {
            $this->previous = $previous;
        }
    }
}