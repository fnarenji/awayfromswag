<?php
/**
 * Created by PhpStorm.
 * User: loick
 * Date: 13/01/15
 * Time: 15:11
 */

namespace app\controllers;


use app\exceptions\ArticleNotFoundException;
use app\exceptions\NewsNotFoundException;
use app\exceptions\NotAuthenticatedException;
use app\exceptions\NotYourArticleException;
use app\models\ArticleModel;
use app\models\UserModel;
use SwagFramework\Helpers\Authentication;
use SwagFramework\Helpers\FormHelper;
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
        $article['category'] = $this->articleModel->getCategory($article['category']);

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

    public function add()

    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $form = FormHelper::generate('article', '/article/add');

        $html = $form->getFormHTML([
            'title' => 'Titre de l\'Actualité',
            'text' => 'Contenu'
        ]);

        $this->getView()->render('article/add', ['form' => $html]);
    }

    public function addPOST()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $form = FormHelper::generate('article', '/article/add');

        $result = $form->validate([
            'title' => 'Titre de l\'Actualité',
            'text' => 'Contenu'
        ]);

        $result['user'] = Authentication::getInstance()->getUserId();

        $this->articleModel->insertNews($result);

        $this->getView()->redirect('/event');
    }

    public function modify()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $id = (int)$this->getParams()[0];
        $article = $this->articleModel->getOneNewsById($id);

        if (empty($article)) {
            throw new ArticleNotFoundException($id);
        }

        if ($article['user'] != Authentication::getInstance()->getUserId()) {
            throw new NotYourArticleException($id);
        }

        $form = FormHelper::generate('article', '/article/modify');

        $form->getField('id')->addAttribute('value', $article['id']);
        $form->getField('title')->addAttribute('value', $article['title']);
        $form->getField('text')->setContent($article['text']);

        $html = $form->getFormHTML([
            'title' => 'Titre de l\'Actualité',
            'text' => 'Contenu'
        ]);

        $this->getView()->render('article/modify', ['form' => $html]);
    }

    public function modifyPOST()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $form = FormHelper::generate('article', '/article/modify');
        $result = $form->validate([
            'id' => '',
            'title' => 'Titre de l\'Actualité',
            'text' => 'Contenu'
        ]);

        $article = $this->articleModel->getOneNewsById($result['id']);

        if (empty($article)) {
            throw new ArticleNotFoundException($result['id']);
        }

        if ($article['user'] != Authentication::getInstance()->getUserId()) {
            throw new NotYourArticleException($result['id']);
        }

        $this->articleModel->updateNews($result);

        $this->getView()->redirect('/article/show/' . $result['id']);
    }
}