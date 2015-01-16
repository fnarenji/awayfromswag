<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 16/01/15
 * Time: 09:31
 */

namespace app\controllers\admin;


use SwagFramework\mvc\Controller;

class AdminController extends Controller {

    /**
     * Load admin index.
     */
    public function index(){
        $this->getView()->render('admin/index');
    }

}