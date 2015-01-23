<?php
/**
 * Created by PhpStorm.
 * User: sknz
 * Date: 13/01/15
 * Time: 21:13
 */

namespace SwagFramework\Exceptions;


use Exception;

class DatabaseConfigurationNotLoadedException extends SwagException
{
    public function __construct($code = 0, Exception $previous = null)
    {
        $message = 'Database configuration not set in database provider. Call connect() ?';

        parent::__construct($message, $code, $previous);
    }
}