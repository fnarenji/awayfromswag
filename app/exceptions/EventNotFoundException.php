<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 27/12/14
 * Time: 23:19
 */

namespace SwagFramework\Exceptions;


class EventNotFoundException extends SwagException
{
    private $previous;

    public function __construct($id, $code = 0, Exception $previous = null)
    {
        $message = 'The event id ' . $id . ' was not found !';

        parent::__construct($message, $code);

        if (!is_null($previous)) {
            $this->previous = $previous;
        }
    }
}