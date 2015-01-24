<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 01/01/15
 * Time: 20:27
 */

namespace app\controllers;

use app\models\ArticleModel;
use app\models\EventModel;
use app\models\UserModel;
use SwagFramework\mvc\Controller;

class HomeController extends Controller
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
        $this->userModel = new UserModel();
        $this->articleModel = new ArticleModel();
        parent::__construct();
    }

    public function index()
    {
        // EVENT
        $modelEvent = new EventModel();
        $events['count'] = $modelEvent->count();
        $events['top'] = $modelEvent->getTop();
        $events['last'] = $modelEvent->getLast();

        // ARTICLE
        $modelArticle = new ArticleModel();
        $article['top'] = $modelArticle->getTop();
        $article['last'] = $modelArticle->getLast();

        $article['top'] = $this->getInfos($article['top']);
        foreach ($article['last'] as &$art) {
            $art = $this->getInfos($art);
        }

        $this->getView()->render('home/index', ['events' => $events, 'article' => $article]);
    }

    private function getInfos(&$article)
    {
        $article['user'] = $this->userModel->getUser($article['user']);
        $article['category'] = $this->articleModel->getCategory($article['category']);

        $postdate = new \DateTime($article['postdate']);
        $article['postdate'] = $postdate->format('d/m/Y à H:i');

        return $article;
    }
}