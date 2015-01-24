<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:11
 */

namespace app\controllers;


use app\exceptions\NewsNotFoundException;
use app\models\ArticleModel;
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
        $this->articleModel = new ArticleModel();
        $this->userModel = new UserModel();
        parent::__construct();
    }

    public function index()
    {
        $articles = $this->articleModel->getNews();

        foreach ($articles as &$article) {
            $article = $this->getInfos($article);
        }

        $this->getView()->render('article/index', array(
            'articles' => $articles
        ));
    }

    private function getInfos($article)
    {
        $article['user'] = $this->userModel->getUser($article['user']);

        $postdate = new \DateTime($article['postdate']);
        $article['postdate'] = $postdate->format('d/m/Y à H:i');

        return $article;
    }

    public function show()
    {
        $id = (int)$this->getParams()[0];

        $article = $this->articleModel->getOneNewsById($id);

        if (empty($article)) {
            throw new NewsNotFoundException($id);
        }

        $article = $this->getInfos($article);

        $this->getView()->render('article/show', array(
            'article' => $article
        ));
    }
}