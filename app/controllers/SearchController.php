<?php
/**
 * Created by PhpStorm.
 * User: sknz
 * Date: la veille du rendu, comme d'hab
 * Time: 21:35
 */

namespace app\controllers;

use SwagFramework\mvc\Controller;

class SearchController extends Controller
{
    public function index()
    {
        $this->getView()->render('search/index');
    }
}