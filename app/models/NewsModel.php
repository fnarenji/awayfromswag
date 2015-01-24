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
    public function getOneNews($id)
    {
        $sql = "SELECT username, text, postdate " .
            "FROM article, user " .
            "WHERE user.id = article.user AND article.id = ?";

        return DatabaseProvider::connection()->query($sql, [$id]);
    }

    /**
     * Return all news
     * @return array
     */
    public function getNews()
    {
        $sql = "SELECT id, username, text, postdate " .
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
    public function insertNews($author, $content, $date, $categorie)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = "INSERT INTO article ('user','text','postdate','category') VALUE (?,?,?,?)";

            DatabaseProvider::connection()->query($sql, [$author, $content, $date, $categorie]);

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
    public function deleteNews($id)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = 'DELETE FROM article WHERE id = ?';

            DatabaseProvider::connection()->query($sql, [$id]);
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
    public function updateNews($id, $content, $date)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = 'UPDATE article SET text = ?, postdate = ? WHERE id = ?';
            DatabaseProvider::connection()->execute($sql, [$content, $date, $id]);

            DatabaseProvider::connection()->commit();
        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }

    }


} 