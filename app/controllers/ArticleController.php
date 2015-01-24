<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:11
 */

namespace app\controllers;


use app\exceptions\NewsNotFoundException;
use app\models\NewsModel;
use app\models\UserModel;
use SwagFramework\mvc\Controller;

class ArticleController extends Controller
{
    /**
     * @var UserModel
     */
    private $userModel;

    /**
     * @var ArticleModel
     */
    private $articleModel;

    function __construct()
    {
        $this->newsModel = new NewsModel();
        $this->userModel = new UserModel();
        parent::__construct();
    }

    public function index()
    {
        $news = $this->newsModel->getNews();

        foreach ($news as &$new) {
            $new = $this->getInfos($new);
        }

        $this->getView()->render('new/index', array(
            'news' => $news
        ));
    }

    private function getInfos($new)
    {
        $new['user'] = $this->userModel->getUser($new['user']);

        $postdate = new \DateTime($new['postdate']);
        $new['postdate'] = $postdate->format('d/m/Y à H:i');

        return $new;
    }

    public function show()
    {
        $id = (int)$this->getParams()[0];

        $new = $this->newsModel->get($id);

        if (empty($new)) {
            throw new NewsNotFoundException($id);
        }

        $new = $this->getInfos($new);

        $this->getView()->render('new/show', array(
            'new' => $new
        ));
    }
}