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
    const INSERT_COMMENT = "INSERT INTO comment (user, message) VALUES (:iduser, :message);";
    const INSERT_COMMENT_FOR_ARTICLE = "INSERT INTO comment_article (id, article) VALUES (:idcomment, :idarticle);";
    const UPDATE_COMMENT_MESSAGE = "UPDATE comment SET message = ? WHERE id = ?";
    const DELETE_COMMENT_ARTICLE = "DELETE FROM comment_article WHERE id = ?";
    const DELETE_COMMENT = "DELETE FROM comment WHERE id = ? ";

    const GET_COMMENTS_FOR_ARTICLE = <<<SQL
SELECT comment.id, comment.message,
        DATE_FORMAT(comment.postdate, '%d/%m/%Y %H:%i:%s') postdate,
        DATE_FORMAT(comment.editdate, '%d/%m/%Y %H:%i:%s') editdate,
        CONCAT(user.username, ' (', user.firstname, ' ', user.lastname, ')') authorFullName
FROM comment
JOIN comment_article ON comment_article.id = comment.id
JOIN user ON user.id = comment.user
WHERE comment_article.article = ?;
SQL;

    const GET_ALL_COMMENTS_FOR_ARTICLE = <<<SQL
SELECT comment.id, article.id, article.title, text, username
FROM comment_article
JOIN article ON article.id = comment_article.article
JOIN comment ON comment.id = comment_article.id
JOIN user ON user.id = comment.user;
SQL;

    /**
     * Return all comment on all article.
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getAllCommentsArticle()
    {
        return DatabaseProvider::connection()->query(self::GET_ALL_COMMENTS_FOR_ARTICLE);
    }

    /**
     * Return all comment from a article.
     * @param $id
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getCommentsForArticle($id)
    {
        return DatabaseProvider::connection()->query(self::GET_COMMENTS_FOR_ARTICLE, [$id]);
    }

    /**
     * Insert a new comment on article
     * @param $iduser int user id
     * @param $idarticle int article id
     * @param $message string comment
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function insertCommentArticle($iduser, $idarticle, $message)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();

            $success = DatabaseProvider::connection()->execute(self::INSERT_COMMENT, [
                'iduser' => $iduser,
                'message' => $message
            ]);

            $newCommentId = DatabaseProvider::connection()->lastInsertId();
            $success = $success && DatabaseProvider::connection()->execute(self::INSERT_COMMENT_FOR_ARTICLE, [
                    'idcomment' => $newCommentId,
                    'idarticle' => $idarticle
                ]);

            DatabaseProvider::connection()->commit();

            return $success;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * Update a  comment on article
     * @param $message the message
     * @param $id the id of comment
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     * @internal param $params
     */
    public function updateCommentArticle($message, $id)
    {
        return DatabaseProvider::connection()->execute(self::UPDATE_COMMENT_MESSAGE, [$message, $id]);
    }

    /**
     * Delete an comment.
     * @param $id int of comment
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function deleteCommentArticle($id)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();
            $success = DatabaseProvider::connection()->execute(self::DELETE_COMMENT_ARTICLE, [$id]);
            $success = $success && DatabaseProvider::connection()->execute(self::DELETE_COMMENT, [$id]);
            DatabaseProvider::connection()->commit();

            return $success;
        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }
}