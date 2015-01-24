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

    /**
     * Return a article
     * @param $id
     * @return mixed
     */
    public function getOneNewsById($id)
    {
        $sql = "SELECT * " .
            "FROM article " .
            "WHERE id=?";

        return DatabaseProvider::connection()->selectFirst($sql, [$id]);
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
        $sql = "SELECT * " .
            "FROM article";

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
     * Delete a article
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
     * Delete a article
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
     * @param $infos
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateNews($infos)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = 'UPDATE article SET text=:text, title=:title WHERE id=:id';
            $state = DatabaseProvider::connection()->execute($sql, $infos);

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

    public function getCategory($id)
    {
        $sql = <<<SQL
SELECT * FROM article_category WHERE id=?;
SQL;

        return DatabaseProvider::connection()->selectFirst($sql, [$id]);

    }
} 