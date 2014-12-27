<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 27/12/14
 * Time: 02:12
 */

namespace SwagFramework\Router;


class RouteCollection extends \SplObjectStorage{

    public function all()
    {
        $tmp = array();

        foreach($this as $objectValue)
            $tmp[] = $objectValue;

        return $tmp;
    }
}