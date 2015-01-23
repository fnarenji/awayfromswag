<?php
/**
 * Created by PhpStorm.
 * User: sknz
 * Date: 1/23/15
 * Time: 8:05 PM
 */

namespace SwagFramework\Helpers;

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

    public function disconnect()
    {
        unset($_SESSION['userName']);
        unset($_SESSION['userId']);
        unset($_SESSION['authDate']);
        session_destroy();
    }

    public function addToContext(array $context = [])
    {
        $authData = [
            'valid' => $this->isAuthenticated(),
        ];

        if ($this->isAuthenticated()) {
            $authData = array_merge($authData,
                [
                    'userName' => $this->getUserName(),
                    'userId' => $this->getUserId(),
                    'authDate' => $this->getAuthDate()
                ]);
        }

        return array_merge($context, ['auth' => $authData]);
    }

    public function isAuthenticated()
    {
        return isset($_SESSION['userName']) && !empty($_SESSION['userName'])
        && isset($_SESSION['userId']) && !empty($_SESSION['userId'])
        && isset($_SESSION['authDate']) && !empty($_SESSION['authDate']);
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