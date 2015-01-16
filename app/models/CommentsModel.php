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

class CommentsModel extends Model
{

    /**
     * Return all comment for all event.
     * @return array
     */
    public function getAllCommentsEvent()
    {
        $sql = "SELECT nameEvent,contents,userName FROM Users,commentE,Comments,event WHERE " .
            "event_idEvent = idEvent AND Comments_idComments = idComments AND idUsers = participateE_Users_idUsers;";

        return DatabaseProvider::connection()->execute($sql, null);
    }


    /**
     * Return all comment for the id event.
     * @param $id
     * @return array
     */
    public function getCommentEvent($id)
    {
        $sql = "SELECT nameEvent,contents,userName FROM Users,commentE,Comments,event WHERE " .
            "event_idEvent = ? AND Comments_idComm<ents = idComments AND idUsers = participateE_Users_idUsers;";

        return DatabaseProvider::connection()->execute($sql, $id);

    }

    public function insertCommentEvent($idparticip, $iduser, $contents, $mark)
    {
        // TO DO
    }

    /**
     * Delete a comment of an event
     * @param $id
     * @return bool
     */
    public function deleteCommentEvent($id)
    {
        $sql = "DELETE FROM commentE WHERE idcommentE = ?";

        DatabaseProvider::connection()->execute($sql, $id);

        return true;
    }
} 