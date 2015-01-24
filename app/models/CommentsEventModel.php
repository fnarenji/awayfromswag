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

    /**
     * Return all comment for all event.
     * @return array
     */
    public function getAllCommentsEvent()
    {
        $sql = <<<'SQL'
SELECT comment.id as commid, comment.message, user.id as useid, user.username, event.name
FROM comment
JOIN user           ON comment.user = user.id
JOIN comment_event  ON comment.id = comment_event.id
JOIN event          ON event.id = comment_event.event
SQL;

        return DatabaseProvider::connection()->query($sql);
    }


    /**
     * Return all comment for the id event.
     * @param $id
     * @return array
     */
    public function getCommentEvent($id)
    {
        $sql = <<<SQL
SELECT comment.id as commid, comment.message, user.id as useid, user.username, event.name
FROM comment
JOIN user           ON comment.user = user.id
JOIN comment_event  ON comment.id = comment_event.id
JOIN event          ON event.id = comment_event.event
                    AND event.id = ?
SQL;

        return DatabaseProvider::connection()->query($sql, [$id]);
    }

    /**
     * Update comment on event
     * @param $params
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateCommentEvent($params)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();

            $sqlComm = "UPDATE comment SET message = ? WHERE id = ?;";
            DatabaseProvider::connection()->query($sqlComm, $params['content'], $params['idcomment']);

            DatabaseProvider::connection()->commit();

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }

    }

    /**
     * Insert in DB a comment for an event.
     * @param $params
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function insertCommentEvent($params)
    {
        try {
            DatabaseProvider::connection()->beginTransaction();

            $sqlComm = "INSERT INTO comment (user, message) VALUES (? , ?);";
            DatabaseProvider::connection()->execute($sqlComm, [$params['iduser'], $params['contents']]);

            $tmp = DatabaseProvider::connection()->lastInsertId();
            $sqlComm = "INSERT INTO comment_event (id, event) VALUES (?, ?)";
            DatabaseProvider::connection()->execute($sqlComm, [$tmp, $params['idevent']]);

            DatabaseProvider::connection()->commit();

            return true;

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
        $sql = "DELETE FROM comment_event WHERE id = ? ";
        $sql2 = "DELETE FROM comment WHERE id = ? ";

        DatabaseProvider::connection()->execute($sql, [$id]);
        return DatabaseProvider::connection()->execute($sql2, [$id]);
    }
} 