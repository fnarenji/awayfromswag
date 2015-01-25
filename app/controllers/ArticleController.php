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
use app\models\CommentsArticleModel;
use app\models\UserModel;
use SwagFramework\Helpers\Authentication;
use SwagFramework\Helpers\FormHelper;
use SwagFramework\Helpers\Input;
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

    /**
     * @var CommentsArticleModel
     */
    private $articleCommentModel;

    function __construct()
    {
        $this->articleModel = $this->loadModel('Article');
        $this->userModel = $this->loadModel('User');
        $this->articleCommentModel = $this->loadModel('CommentsArticle');
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
        $comments = $this->articleCommentModel->getCommentsForArticle($id);

        $this->getView()->render('article/show', [
            'article' => $article,
            'comments' => $comments
        ]);
    }

    public function add()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $form = FormHelper::generate('article', '/article/add');

        $html = $form->getFormHTML([
            'title' => 'Titre de l\'Actualité',
            'image' => 'URL Image',
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
            'image' => 'URL Image',
            'text' => 'Contenu'
        ]);


        $result['user'] = Authentication::getInstance()->getUserId();

        // HARDCODE IS BAD
        $result['category'] = 1;

        $this->articleModel->insertNews($result);

        $this->getView()->redirect('/article');
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

        if ($article['user'] != Authentication::getInstance()->getUserId() && !(Authentication::getInstance()->getOptionOr('accessLevel', 0) == 1)) {
            throw new NotYourArticleException($id);
        }

        $form = FormHelper::generate('article', '/article/modify');

        $form->getField('id')->addAttribute('value', $article['id']);
        $form->getField('title')->addAttribute('value', $article['title']);
        $form->getField('text')->setContent($article['text']);
        $form->getField('image')->setContent($article['image']);

        $html = $form->getFormHTML([
            'title' => 'Titre de l\'Actualité',
            'image' => 'URL Image',
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

        if ($article['user'] != Authentication::getInstance()->getUserId() && !(Authentication::getInstance()->getOptionOr('accessLevel', 0) == 1)) {
            throw new NotYourArticleException($result['id']);
        }

        $this->articleModel->updateNews($result);

        $this->getView()->redirect('/article/show/' . $result['id']);
    }

    public function comment()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $id = (int)$this->getParams()[0];
        $this->getView()->redirect('/article/show/' . $id);
    }

    public function commentPOST()
    {
        if (!Authentication::getInstance()->isAuthenticated()) {
            throw new NotAuthenticatedException();
        }

        $id = (int)$this->getParams()[0];

        $this->articleCommentModel->insertCommentArticle(Authentication::getInstance()->getUserId(), $id, Input::post('message'));

        $this->getView()->redirect('/article/show/' . $id);
    }
}