<?php
/**
 * Created by PhpStorm.
 * User: sknz
 * Date: 13/01/15
 * Time: 16:13
 */

namespace SwagFramework\Exceptions;


class MissingConfigEntryException extends SwagException {
    /**
     * @param string $fileName
     * @param int $configEntry
     * @param \Exception $key
     * @param int $code
     * @param Exception $previous
     */
    public function __construct($fileName, $configEntry, $key, $code = 0, Exception $previous = null)
    {
        $message = 'Missing config entry ' . $configEntry . ' from config file ' . $fileName . '!';

        parent::__construct($message, $code);

        if (!is_null($previous)) {
            $this->previous = $previous;
        }
    }
}