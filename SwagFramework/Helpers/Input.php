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
        return self::quote_smart($_GET[$key]);
    }

    /**
     * Fonction qui protège la variable passée en paramètre des injections SQL
     * et des caractères spéciaux.
     *
     * @author Mickaël Martin Nevot
     * http://mickael-martin-nevot.com/iut-informatique/programmation-web-c%C3%B4t%C3%A9-serveur/s24-cm2-php-interm%C3%A9diaire.pdf
     */
    private static function quote_smart($value)
    {
        // PDO makes this a bit useless, so commented because not tested
//        $value = utf8_encode($value);
//
//        // Protection concernant Stripslashes
//        if (get_magic_quotes_gpc()) {
//            $value = stripslashes($value);
//        }
//        // Protection si ce n'est pas une valeur numérique ou une chaîne numérique
//        if (!is_numeric($value)) {
//            $value = '\'' . @mysql_real_escape_string($value) . '\'';
//        }
        return $value;
    }

    /**
     * @param $key
     * @param bool $optional
     * @return null
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
        return self::quote_smart($_POST[$key]);
    }

    /**
     * @param $key
     * @param bool $optional
     * @return null
     * @throws InputNotSetException
     */
    public static function session($key, $optional = false)
    {
        if (!isset($_SESSION[$key]) || empty($_SESSION[$key])) {
            if (!$optional)
                throw new InputNotSetException('$_SESSION', $key);
            return null;
        }

        return self::quote_smart($_SESSION[$key]);
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