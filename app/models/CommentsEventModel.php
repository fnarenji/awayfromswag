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
    const SELECT_ALL_COMMENTS = <<<'SQL'
SELECT comment.id AS commid, comment.message, user.id AS useid, user.username, event.name
FROM comment
JOIN user           ON comment.user = user.id
JOIN comment_event  ON comment.id = comment_event.id
JOIN event          ON event.id = comment_event.event
SQL;
    const GET_COMMENT_FOR_EVENT = <<<SQL
SELECT comment.id AS commid, comment.message, user.id AS useid, user.username, event.name
FROM comment
JOIN user           ON comment.user = user.id
JOIN comment_event  ON comment.id = comment_event.id
JOIN event          ON event.id = comment_event.event
                    AND event.id = ?
SQL;
    const UPDATE_COMMENT_EVENT = "UPDATE comment SET message = ? WHERE id = ?;";
    const INSERT_COMMENT_EVENT = "INSERT INTO comment_event (id, event) VALUES (?, ?)";
    const NEW_COMMENT = "INSERT INTO comment (user, message) VALUES (? , ?);";
    const DELETE_COMMENT_EVENT = "DELETE FROM comment_event WHERE id = ? ";
    const DELETE_COMMENT = "DELETE FROM comment WHERE id = ? ";

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
    public function getCommentEvent($id)
    {
        return DatabaseProvider::connection()->query(self::GET_COMMENT_FOR_EVENT, [$id]);
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
     * Insert in DB a comment for an event.
     * @param $params
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function insertCommentEvent($params)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();

            $result = DatabaseProvider::connection()->execute(self::NEW_COMMENT, [$params['iduser'], $params['contents']]);

            $tmp = DatabaseProvider::connection()->lastInsertId();

            $result = $result && DatabaseProvider::connection()->execute(self::INSERT_COMMENT_EVENT, [$tmp, $params['idevent']]);

            if ($result)
                DatabaseProvider::connection()->commit();

            return $result;
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