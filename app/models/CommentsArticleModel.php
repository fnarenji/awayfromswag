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
        $sql = "SELECT comment.id, article.id, article.title, text, username FROM user, comment_article, comment, article WHERE " .
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
        $sql = "SELECT comment.id, article.id, article.title, text, username FROM user,comment_article,comment,article WHERE " .
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
            DatabaseProvider::connection()->execute($sqlComm, [$param['iduser'], $param['contents']]);

            $newCommentId = DatabaseProvider::connection()->lastInsertId();
            $sqlComm = "INSERT INTO comment_article (id, article) VALUES (? , ?);";
            DatabaseProvider::connection()->execute($sqlComm, [$newCommentId, $param['idarticle']]);

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
            DatabaseProvider::connection()->execute($sqlComm, [$params['content'], $params['idcomment']]);

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
    public function deleteCommentArticle($id)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();
            $sql = "DELETE FROM comment_article WHERE id = ? ";

            DatabaseProvider::connection()->execute($sql, [$id]);

            $sql = "DELETE FROM comment WHERE id = ? ";

            DatabaseProvider::connection()->execute($sql, [$id]);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }
}