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

class NewsModel extends Model
{

    /**
     * Return a news
     * @param $id
     * @return mixed
     */
    public function getOneNewsById($id)
    {
        $sql = "SELECT article.id,title, username, text, postdate " .
            "FROM article, user " .
            "WHERE user.id = article.user AND article.id = ?";

        return DatabaseProvider::connection()->selectFirst($sql, [$id]);
    }

    /**
     * Return a news
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
     * Return all news
     * @return array
     */
    public function getNews()
    {
        $sql = "SELECT article.id, username, text, postdate " .
            "FROM article, user " .
            "WHERE user.id = article.user";

        return DatabaseProvider::connection()->query($sql);
    }

    /**
     * Insert new article in D
     * @param $author
     * @param $content
     * @param $date
     * @param $categorie
     * @return bool
     */
    public function insertNews($infos)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = <<<SQL
INSERT INTO article (user,title,text,postdate,category) VALUES (:user, :title, :text, :postdate, :category);
SQL;

            DatabaseProvider::connection()->execute($sql, $infos);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * Delete a news
     * @param $id
     * @return bool
     */
    public function deleteNewsById($id)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = 'DELETE FROM article WHERE id = ?';

            DatabaseProvider::connection()->execute($sql, [$id]);
            DatabaseProvider::connection()->commit();

            return true;
        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * Delete a news
     * @param $id
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function deleteNewsByName($name)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = 'DELETE FROM article WHERE title = ?';

            DatabaseProvider::connection()->execute($sql, [$name]);
            DatabaseProvider::connection()->commit();

            return true;
        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * Update a news
     * @param $id
     * @param $content
     * @param $date
     * @return bool
     */
    public function updateNewsById($id, $content, $date)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = 'UPDATE article SET text = ?, postdate = ? WHERE id = ?';
            $state  = DatabaseProvider::connection()->execute($sql, [$content, $date, $id]);

            DatabaseProvider::connection()->commit();

            return $state;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }

    }



    public function updateNewsByName($name,$date, $content)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = 'UPDATE article SET text = ?, postdate = ? WHERE title = ?';
            $state = DatabaseProvider::connection()->execute($sql, [$content,$date, $name]);

            DatabaseProvider::connection()->commit();

            return $state;
        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }

    }
} 