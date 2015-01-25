<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/01/15
 * Time: 12:11
 */

namespace app\models;

use SwagFramework\Database\DatabaseProvider;
use SwagFramework\mvc\Model;

class ArticleModel extends Model
{
    const INSERT_ARTICLE = "INSERT INTO article (user,title,text,image,postdate,category) VALUES (:user, :title, :text, :image, NOW(), :category);";
    const DELETE_ARTICLE = 'DELETE FROM article WHERE id = ?';
    const DELETE_ARTICLE_BY_NAME = 'DELETE FROM article WHERE title = ?';
    const UPDATE_ARTICLE_TEXT = 'UPDATE article SET text=:text, title=:title WHERE id=:id';
    const UPDATE_ARTICLE_TEXT_BY_NAME = 'UPDATE article SET text = ?, postdate = ? WHERE title = ?';

    const SEARCH_ARTICLE = <<<SQL
SELECT article.*
FROM article
JOIN user ON article.user = user.id
JOIN article_category ON article.category = article_category.id
WHERE MATCH(article.title, article.text) AGAINST (:query)
  OR MATCH(user.username, user.firstname, user.lastname) AGAINST (:query)
  OR article.id = :query
  OR MATCH(article_category.name) AGAINST (:query)
SQL;

    const SELECT_ARTICLE = <<<SQL
SELECT article.*, CONCAT(user.username, ' (', user.firstname, ' ', user.lastname, ')') as authorFullName
FROM article
JOIN user ON user.id = article.user
WHERE article.id = ?
SQL;

    const GET_CATEGORY = <<<SQL
SELECT * FROM article_category WHERE id=?;
SQL;

    const SELECT_TOP_ARTICLE = <<<SQL
SELECT article.*, CONCAT(user.username, ' (', user.firstname, ' ', user.lastname, ')') AS authorFullName
FROM article
JOIN user ON user.id = article.user
ORDER BY postdate DESC
LIMIT 1;
SQL;

    const SELECT_LATEST_ARTICLES = <<<SQL
SELECT article.*, CONCAT(user.username, ' (', user.firstname, ' ', user.lastname, ')') AS authorFullName
FROM article
JOIN user ON user.id = article.user
ORDER BY postdate DESC
LIMIT 1,4;
SQL;
    const SELECT_ARTICLES = <<<SQL
SELECT article.*, CONCAT(user.username, ' (', user.firstname, ' ', user.lastname, ')') AS authorFullName
FROM article
JOIN user ON user.id = article.user
SQL;

    /**
     * Return a article
     * @param $id
     * @return mixed
     */
    public function getOneNewsById($id)
    {
        return DatabaseProvider::connection()->selectFirst(self::SELECT_ARTICLE, [$id]);
    }

    /**
     * Return a article
     * @param $name
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getOneNewsByName($name)
    {
        $sql = "SELECT article.id,title, username, text, postdate " .
            "FROM article, user " .
            "WHERE user.id = article.user AND title = ?";

        return DatabaseProvider::connection()->selectFirst($sql, [$name]);
    }


    /**
     * Return all article
     * @return array
     */
    public function getNews()
    {
        return DatabaseProvider::connection()->query(self::SELECT_ARTICLES);
    }

    /**
     * @param $infos
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function insertNews($infos)
    {
        return DatabaseProvider::connection()->execute(self::INSERT_ARTICLE, $infos);
    }

    /**
     * @param $id
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function deleteNewsById($id)
    {
        return DatabaseProvider::connection()->execute(self::DELETE_ARTICLE, [$id]);
    }

    /**
     * Delete a article
     * @param $id
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function deleteNewsByName($name)
    {
        return DatabaseProvider::connection()->execute(self::DELETE_ARTICLE_BY_NAME, [$name]);
    }

    /**
     * @param $infos
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateNews($infos)
    {
        return DatabaseProvider::connection()->execute(self::UPDATE_ARTICLE_TEXT, $infos);
    }

    public function updateNewsByName($name,$date, $content)
    {
        return DatabaseProvider::connection()->execute(self::UPDATE_ARTICLE_TEXT_BY_NAME, [$content, $date, $name]);
    }

    public function getCategory($id)
    {
        return DatabaseProvider::connection()->selectFirst(self::GET_CATEGORY, [$id]);

    }

    public function getTop()
    {
        return DatabaseProvider::connection()->selectFirst(self::SELECT_TOP_ARTICLE);
    }

    public function getLast()
    {
        return DatabaseProvider::connection()->query(self::SELECT_LATEST_ARTICLES, []);
    }

    public function search($query)
    {
        return DatabaseProvider::connection()->query(self::SEARCH_ARTICLE, ['query' => $query]);
    }
} 