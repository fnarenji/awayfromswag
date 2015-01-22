<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 16/01/15
 * Time: 09:31
 */

namespace app\controlers\admin;


use SwagFramework\mvc\Controler;

class AdminControler extends Controler
{

    /**
     * Load admin index.
     */
    public function index()
    {
        $this->getView()->render('admin/index');
    }

}