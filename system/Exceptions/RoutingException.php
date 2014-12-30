<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 27/12/14
 * Time: 23:19
 */

namespace SwagFramework\Routing;


class RoutingException extends \Exception
{
    private $previous;

    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code);

        if (!is_null($previous))
            $this->previous = $previous;
    }

}