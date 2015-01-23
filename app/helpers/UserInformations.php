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
use SwagFramework\Helpers\Input;

class UserInformations
{
    private static $informations = array();

    protected function __construct(){}
    private function __clone(){}

    public function getInformations()
    {
        if(empty(self::$informations))
            self::setInformations();

        return self::$informations;
    }

    private function setInformations()
    {
        try
        {
            $model = new UserModel();
            $input = new Input();
            self::$informations = $model->getUser($input->session('id'));
        }
        catch (InputNotSetException $e)
        {
            $e->getMessage();
        }
    }
}