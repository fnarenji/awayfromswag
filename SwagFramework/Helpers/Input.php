<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 29/12/14
 * Time: 16:02
 */

namespace SwagFramework\Helpers;


use SwagFramework\Exceptions\InputNotSetException;

class Input
{
    /**
     * get var $_GET
     * @param $key
     * @return mixed
     * @throws InputNotSetException
     */
    public static function get($key, $optional = false)
    {
        if (!isset($_GET[$key]) || empty($_GET[$key])) {
            if (!$optional)
                throw new InputNotSetException('$_GET', $key);
            return null;
        }
        //TODO: Protect input $_GET
        return $_GET[$key];
    }

    /**
     * get var $_POST
     * @param $key
     * @return mixed
     * @throws InputNotSetException
     */
    public static function post($key, $optional = false)
    {
        if (!isset($_POST[$key]) || empty($_POST[$key])) {
            if (!$optional)
                throw new InputNotSetException('$_POST', $key);
            return null;
        }
        //TODO: Protect input $_GET
        return $_POST[$key];
    }

    /**
     * @param $key
     * @return mixed
     * @throws InputNotSetException
     */
    public static function session($key, $optional = false)
    {
        if (!isset($_SESSION[$key]) || empty($_SESSION[$key])) {
            if (!$optional)
                throw new InputNotSetException('$_SESSION', $key);
            return null;
        }

        return $_SESSION[$key];
    }

    public static function getPost()
    {
        if (empty($_POST)) {
            throw new InputNotSetException('$_POST', '');
        }

        return $_POST;
    }

    /**
     * get user ip
     * @return mixed
     */
    public static function userIP()
    {
        return $_SERVER['REMOTE_ADDR'];
    }
}