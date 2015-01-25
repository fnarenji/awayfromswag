<?php
/**
 * Created by PhpStorm.
 * User: sknz
 * Date: 13/01/15
 * Time: 20:43
 */

namespace SwagFramework\Helpers;

class BaseViewContextProvider
{
    static private $contextProvider;

    private function __construct()
    {
    }

    /**
     * @return array context information
     */
    public static function provide()
    {
        if (!isset(self::$contextProvider) || empty(self::$contextProvider))
            return [];

        return call_user_func(self::$contextProvider);
    }

    /**
     * Sets the context provider. A function that is called to generate context every time a view is rendered
     * @param callable $contextProvider
     */
    public static function setProvider(callable $contextProvider)
    {
        self::$contextProvider = $contextProvider;
    }

    private function __clone()
    {
    }
}