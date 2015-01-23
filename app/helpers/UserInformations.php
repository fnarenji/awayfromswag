<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 23/01/15
 * Time: 15:53
 */

namespace app\helpers;


use app\models\UserModel;
use SwagFramework\Exceptions\InputNotSetException;
use SwagFramework\helpers\Authentication;

class UserInformations
{
    private static $informations = array();

    protected function __construct()
    {
    }

    public function getInformations()
    {
        if (empty(self::$informations)) {
            self::setInformations();
        }

        return self::$informations;
    }

    private function setInformations()
    {
        try {
            $model = new UserModel();

            self::$informations = $model->getUser(Authentication::getInstance()->getUserId());
        } catch (InputNotSetException $e) {
            $e->getMessage();
        }
    }

    private function __clone()
    {
    }
}