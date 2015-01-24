<?php
/**
 * Created by PhpStorm.
 * User: loicpauletto
 * Date: 23/01/15
 * Time: 16:54
 */

namespace app\models;


use SwagFramework\Database\DatabaseProvider;
use SwagFramework\mvc\Model;

class CommentsArticleModel extends Model
{

    /**
     * Return all comment on all article.
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getAllCommentsArticle()
    {
        $sql = "SELECT article.id, comment.title, text, username FROM user, comment_article, comment, article WHERE " .
            "comment_article.article = article.id AND comment_article.id = comment.id AND user.id = comment.user ;";

        return DatabaseProvider::connection()->query($sql);
    }

    /**
     * Return all comment from a article.
     * @param $id
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getCommentArticle($id)
    {
        $sql = "SELECT title, text, username FROM user,comment_article,comment,article WHERE " .
            "comment_article.article = ? AND comment_article.id = comment.id AND user.id = comment.user ;";

        return DatabaseProvider::connection()->query($sql, [$id]);

    }

    /**
     * Insert a new comment on article
     * @param $param
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function insertCommentArticle($param)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();

            $sqlComm = "INSERT INTO comment (user, message) VALUES (? , ?);";
            DatabaseProvider::connection()->query($sqlComm, [$param['iduser'], $param['contents']]);

            $newCommentId = DatabaseProvider::connection()->lastInsertId();
            $sqlComm = "INSERT INTO comment_event (id, event) VALUES (? , ?);";
            DatabaseProvider::connection()->query($sqlComm, [$newCommentId, $param['idarticle']]);

            DatabaseProvider::connection()->commit();

            return true;
        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * Update a  comment on article
     * @param $params
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateCommentArticle($params)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();

            $sqlComm = "UPDATE comment SET message = ? WHERE id = ?";
            DatabaseProvider::connection()->query($sqlComm, [$params['content'], $params['idcomment']]);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * Delete an comment.
     * @param $id int of comment
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function deleteCommentEvent($id)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();
            $sql = "DELETE FROM comment_article WHERE id = ? ";

            DatabaseProvider::connection()->query($sql, [$id]);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }
}