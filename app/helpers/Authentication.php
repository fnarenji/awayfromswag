<?php
/**
 * Created by PhpStorm.
 * User: sknz
 * Date: 1/23/15
 * Time: 8:05 PM
 */

namespace app\helpers;

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

    public function setAuthenticated($userName, $userId)
    {
        $_SESSION['userName'] = $userName;
        $_SESSION['userId'] = $userId;
        $_SESSION['authDate'] = new \DateTime();
    }

    public function isAuthenticated()
    {
        return isset($_SESSION['userName']) && !empty($_SESSION['userName'])
        && isset($_SESSION['userId']) && !empty($_SESSION['userId'])
        && isset($_SESSION['authDate']) && !empty($_SESSION['authDate']);
    }

    public function addToContext(array $context)
    {
        return array_merge($context, ['auth' => [
            'userName' => $this->getUserName(),
            'userId' => $this->getUserId(),
            'authDate' => $this->getAuthDate()
        ]]);
    }

    public function getUserName()
    {
        return $_SESSION['userName'];
    }

    public function getUserId()
    {
        return $_SESSION['userId'];
    }

    public function getAuthDate()
    {
        return $_SESSION['authDate'];
    }

    private function __clone()
    {

    }
}