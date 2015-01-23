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
        $sql = "SELECT id, title, text, username FROM user,comment_article,comment,article WHERE " .
            "comment_article.article = article.id AND comment_article.id = comment.id AND user.id = comment.user ;";

        return DatabaseProvider::connection()->execute($sql, []);
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

        return DatabaseProvider::connection()->execute($sql, [$id]);

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

            $sqlComm = "INSERT INTO comments ('user','contents') VALUES (? , ?);";
            DatabaseProvider::connection()->execute($sqlComm, [$param['iduser'], $param['contents']]);

            $tmp = $this->getIdComment();
            $sqlComm = "INSERT INTO comment_event ('id','article') VALUES (? , ?);";
            DatabaseProvider::connection()->execute($sqlComm, [$tmp, $param['idarticle']]);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
        }

        return false;
    }

    /**
     * Return id of last comment
     * @return mixed
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    private function getIdComment()
    {
        $sql = "SELECT MAX(id) FROM comment";

        return DatabaseProvider::connection()->execute($sql, [])[0];
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

            $sqlComm = "UPDATE comments  SET contents = ? WHERE id = ? ;";
            DatabaseProvider::connection()->execute($sqlComm, [$params['content'], $params['idcomment']]);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
        }

        return false;
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

            DatabaseProvider::connection()->execute($sql, [$id]);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
        }

        return true;
    }
}