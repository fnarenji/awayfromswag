<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 24/01/15
 * Time: 00:56
 */

namespace app\helpers;


use app\models\UserModel;

class PrivacyCalculator
{
    private static $privacy = array();

    public static function calculate($id)
    {
        if(!empty(self::$privacy))
            return self::$privacy;

        $model = new UserModel();
        $user = $model->getUser($id);

        $privacy = (int)$user['privacy'];

        $x = 14;
        $user['privacy'] = array();
        foreach($user as $key => $value){
            if($key == 'privacy') break;

            $exp = 2**$x;

            if($exp <= $privacy){
                $user['privacy'][$key . 'Privacy'] = true;
                $privacy -= $exp;
            } else {
                $user['privacy'][$key . 'Privacy'] = false;
            }

            --$x;
        }
        self::$privacy = $user['privacy'];
        return $user['privacy'];
    }
}