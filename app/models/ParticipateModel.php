<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/01/15
 * Time: 12:12
 */

namespace app\models;

use SwagFramework\Database\DatabaseProvider;
use SwagFramework\mvc\Model;

class ParticipateModel extends Model
{

    /**
     * Return all participant to all event.
     * @return array
     */
    public function getEventsParticipations()
    {
        $sql = 'SELECT event_user.id, username, name FROM user,event,event_user WHERE user.id = event_user.user AND event.id = event_user.id;';

        return DatabaseProvider::connection()->query($sql);
    }

    /**
     * Return all participant of a event
     * @param $id int of event
     * @return array
     */
    public function getEventParticipation($id)
    {
        $sql = 'SELECT username,name FROM user,event,event_user WHERE user.id = event_user.user AND event.id = event_user.id AND event_user.id = ? ;';

        return DatabaseProvider::connection()->query($sql, [$id]);

    }

    /**
     * Return all participation of a user
     * @param $id int of user
     * @return array
     */
    public function getUserParticipations($id)
    {
        $sql = 'SELECT name FROM event,event_user WHERE event.id = event_user.id AND event_user.user = ? ; ';

        return DatabaseProvider::connection()->selectFirst($sql, [$id]);
    }

    /**
     * Insert a new participation to an event
     * @param $idEvent
     * @param $idUser
     * @param $joindate
     * @return bool
     */
    public function insertEventParticipation($id, $user)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();

            $sql = <<<SQL
INSERT INTO event_user (id, user, joindate) VALUES (?, ?, NOW());
SQL;

            DatabaseProvider::connection()->execute($sql, [$id, $user]);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }


    /**
     * Delete a participation to an event
     * @param $idEvent
     * @param $idUser
     * @return bool
     */
    public function deleteEventParticipation($idEvent, $idUser)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();
            $sql = 'DELETE FROM event_user WHERE user = ? AND id = ?';

            DatabaseProvider::connection()->execute($sql, [$idUser, $idEvent]);
            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * Return all participant at one or more challenge
     * @return array
     */
    /*public function getAllChanllengesParticipations()
    {
        $sql = 'SELECT userName,name FROM challenges,ParticipationC,Users WHERE idUsers = Users_idUsers AND challenges_idchallenges =  idchallenges';

        return DatabaseProvider::connection()->execute($sql, null);
    }*/

    /**
     * Return all participant of one challenge.
     * @param $id
     * @return array
     */
    /*public function getOneChanllengesParticipations($id)
    {
        $sql = 'SELECT userName FROM ParticipationC,Users WHERE idUsers = Users_idUsers AND challenges_idchallenges =  ? ';

        return DatabaseProvider::connection()->execute($sql, $id);

    }*/

    /**
     * Return all challenges of one participant.
     * @param $id
     * @return array
     */
    /*public function getChanllengesParticipationUser($id)
    {
        $sql = 'SELECT name FROM challenges,ParticipationC WHERE Users_idUsers = ? AND challenges_idchallenges =  idchallenges';

        return DatabaseProvider::connection()->execute($sql, $id);

    }*/

    /**
     * Insert new participation in DB
     * @param $idUsers
     * @param $idchallenges
     * @return bool
     */
    /*public function insertChanllengesParticipation($idUsers, $idchallenges)
    {
        $sql = 'INSERT INTO ParticipationC (`Users_idUsers`, `challenges_idchallenges`) VALUES ?,?';

        DatabaseProvider::connection()->execute($sql, $idUsers, $idchallenges);

        return true;
    }*/

    /**
     * Delete a challenge.
     * @param $id
     * @return bool
     */
    /*public function deleteChanllengesParticipation($id)
    {
        $sql = 'DELETE FROM ParticipationC WHERE idParticipation = ?';

        DatabaseProvider::connection()->execute($sql, $id);

        return true;
    }*/


} 