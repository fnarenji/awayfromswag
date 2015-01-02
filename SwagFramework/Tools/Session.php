<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 30/12/14
 * Time: 20:23
 */

namespace SwagFramework\Tools;


class Session
{
    /**
     * get session
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * get session
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
}