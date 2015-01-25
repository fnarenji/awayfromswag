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
use app\models\LittleModel;
use app\models\UserModel;
use SwagFramework\mvc\Controller;

class HomeController extends Controller
{
    /**
     * @var EventModel
     */
    private $modelEvent;

    /**
     * @var UserModel
     */
    private $userModel;

    /**
     * @var LittleModel
     */
    private $modelConnect;

    function __construct()
    {
        $this->modelEvent = new EventModel();
        $this->userModel = new UserModel();
        $this->articleModel = new ArticleModel();
        $this->modelConnect = new LittleModel();
        parent::__construct();
    }

    public function index()
    {
        // EVENT
        $tmp = $this->modelConnect->checkConnect();
        $events['top'] = $this->modelEvent->getTop();
        $events['last'] = $this->modelEvent->getLast();
        $events['nbConnect'] = (int)$tmp['COUNT(*)'];

        // ARTICLE
        $modelArticle = new ArticleModel();
        $article['top'] = $modelArticle->getTop();
        $article['last'] = $modelArticle->getLast();

        $article['top'] = $this->getInfos($article['top']);
        $article['top']['text'] = substr($article['top']['text'], 0, strpos($article['top']['text'], ' ', 600));

        foreach ($article['last'] as &$art) {
            $art['text'] = substr($art['text'], 0, strpos($art['text'], ' ', 70));
            $art['text'] .=  ' ... ';
            $art = $this->getInfos($art);
        }
        
        $this->getView()->render('home/index', ['events' => $events, 'article' => $article]);
    }

    private function getInfos($article)
    {
        $article['user'] = $this->userModel->getUser($article['user']);
        $article['category'] = $this->articleModel->getCategory($article['category']);

        $postdate = new \DateTime($article['postdate']);
        $article['postdate'] = $postdate->format('d/m/Y à H:i');

        return $article;
    }
}