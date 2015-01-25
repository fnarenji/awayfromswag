<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 10/01/15
 * Time: 16:35
 */

namespace app\models;

use SwagFramework\Database\DatabaseProvider;
use SwagFramework\mvc\Model;

class CommentsEventModel extends Model
{
    const INSERT_COMMENT = "INSERT INTO comment (user, message) VALUES (:iduser, :message);";
    const INSERT_COMMENT_FOR_EVENT = "INSERT INTO comment_event (id, event) VALUES (:idcomment, :idevent);";
    const UPDATE_COMMENT_EVENT = "UPDATE comment SET message = ? WHERE id = ?;";
    const INSERT_COMMENT_EVENT = "INSERT INTO comment_event (id, event) VALUES (?, ?)";
    const NEW_COMMENT = "INSERT INTO comment (user, message) VALUES (? , ?);";
    const DELETE_COMMENT_EVENT = "DELETE FROM comment_event WHERE id = ? ";
    const DELETE_COMMENT = "DELETE FROM comment WHERE id = ? ";

    const SELECT_ALL_COMMENTS = <<<'SQL'
SELECT comment.id AS commid, comment.message, user.id AS useid, user.username, event.name
FROM comment
JOIN user           ON comment.user = user.id
JOIN comment_event  ON comment.id = comment_event.id
JOIN event          ON event.id = comment_event.event
SQL;

    const GET_COMMENTS_FOR_EVENT = <<<SQL
SELECT comment.id, comment.message,
        DATE_FORMAT(comment.postdate, '%d/%m/%Y %H:%i:%s') postdate,
        DATE_FORMAT(comment.editdate, '%d/%m/%Y %H:%i:%s') editdate,
        CONCAT(user.username, ' (', user.firstname, ' ', user.lastname, ')') authorFullName
FROM comment
JOIN comment_event ON comment_event.id = comment.id
JOIN user ON user.id = comment.user
WHERE comment_event.event = ?;
SQL;

    /**
     * Return all comment for all event.
     * @return array
     */
    public function getAllCommentsEvent()
    {
        return DatabaseProvider::connection()->query(self::SELECT_ALL_COMMENTS);
    }


    /**
     * Return all comment for the id event.
     * @param $id
     * @return array
     */
    public function getCommentsForEvent($id)
    {
        return DatabaseProvider::connection()->query(self::GET_COMMENTS_FOR_EVENT, [$id]);
    }

    /**
     * Update comment on event
     * @param $params
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateCommentEvent($params)
    {
        DatabaseProvider::connection()->query(self::UPDATE_COMMENT_EVENT, $params['content'], $params['idcomment']);
    }

    /**
     * Insert a new comment on article
     * @param $iduser int user id
     * @param $idevent int event id
     * @param $message string comment
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function insertCommentEvent($iduser, $idevent, $message)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();

            $success = DatabaseProvider::connection()->execute(self::INSERT_COMMENT, [
                'iduser' => $iduser,
                'message' => $message
            ]);

            $newCommentId = DatabaseProvider::connection()->lastInsertId();
            $success = $success && DatabaseProvider::connection()->execute(self::INSERT_COMMENT_FOR_EVENT, [
                    'idcomment' => $newCommentId,
                    'idevent' => $idevent
                ]);

            DatabaseProvider::connection()->commit();

            return $success;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     *  Delete a comment.
     * @param $id int of comment
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function deleteCommentEvent($id)
    {
        return DatabaseProvider::connection()->execute(self::DELETE_COMMENT_EVENT, [$id])
        && DatabaseProvider::connection()->execute(self::DELETE_COMMENT, [$id]);
    }
} 