<?php
/**
 * Created by PhpStorm.
 * User: sknz
 * Date: 1/23/15
 * Time: 8:05 PM
 */

namespace SwagFramework\helpers;


class Authentication
{
    private static $instance;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (!(self::$instance instanceof self))
            self::$instance = new self();

        return self::$instance;
    }

    private function __clone()
    {

    }
}