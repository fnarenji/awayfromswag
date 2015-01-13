<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 09/01/15
 * Time: 12:12
 */

namespace app\models;

use SwagFramework\mvc\Model;

class participateModel extends Model {

    /**
     * Return all participant.
     * @return array
     */
    public function getEventsParticipations(){
        $sql = 'SELECT userName,nameEvent FROM Users,event,participateE WHERE idUsers = Users_idUsers AND idEvent = event_idEvent; ';

        return $this->getDatabase()->execute($sql,null);
    }

    /**
     * Return all participant of a event
     * @param $id of event
     * @return array
     */
    public function getEventParticipation($id){
        $sql = 'SELECT userName,nameEvent FROM Users,event,participateE WHERE idUsers = Users_idUsers AND idEvent = event_idEvent AND event_idEvent = ? ; ';

        return $this->getDatabase()->execute($sql,$id);

    }

    /**
     * Return all participation of a user
     * @param $id of user
     * @return array
     */
    public function getUserParticipations($id){
        $sql = 'SELECT nameEvent FROM event,participateE WHERE Users_idUsers = ? AND idEvent = event_idEvent ; ';

        return $this->getDatabase()->execute($sql,$id);
    }

    /**
     * Insert a new participation to an event
     * @param $idEvent
     * @param $idUser
     * @param $nbAvailable Place available after insert ( can be optionnal ? )
     * @return bool
     */
    public function insertEventParticipation($idEvent,$idUser,$nbAvailable){
        $sql = 'INSERT INTO participateE VALUES ?,?,?';

        $this->getDatabase()->execute($sql,$idEvent,$idUser,$nbAvailable);

        return true;
    }


    /**
     * Delete a participation to an event
     * @param $idEvent
     * @param $idUser
     * @return bool
     */
    public function deleteEventParticipation($idEvent,$idUser){
        $sql = 'DELETE FROM participateE WHERE Users_idUsers = ? AND event_idEvent = ?';

        $this->getDatabase()->execute($sql,$idUser,$idEvent);

        return true;
    }

    /**
     * Return all participant at one or more challenge
     * @return array
     */
    public function getAllChanllengesParticipations(){
        $sql = 'SELECT userName,name FROM challenges,ParticipationC,Users WHERE idUsers = Users_idUsers AND challenges_idchallenges =  idchallenges';

        return $this->getDatabase()->execute($sql,null);
    }

    /**
     * Return all participant of one challenge.
     * @param $id
     * @return array
     */
    public function getOneChanllengesParticipations($id){
        $sql = 'SELECT userName FROM ParticipationC,Users WHERE idUsers = Users_idUsers AND challenges_idchallenges =  ? ';

        return $this->getDatabase()->execute($sql,$id);

    }

    /**
     * Return all challenges of one participant.
     * @param $id
     * @return array
     */
    public function getChanllengesParticipationUser($id){
        $sql = 'SELECT name FROM challenges,ParticipationC WHERE Users_idUsers = ? AND challenges_idchallenges =  idchallenges';

        return $this->getDatabase()->execute($sql,$id);

    }

    /**
     * Insert new participation in DB
     * @param $idUsers
     * @param $idchallenges
     * @return bool
     */
    public function insertChanllengesParticipation($idUsers,$idchallenges){
        $sql = 'INSERT INTO ParticipationC (`Users_idUsers`, `challenges_idchallenges`) VALUES ?,?';

        $this->getDatabase()->execute($sql,$idUsers,$idchallenges);

        return true;
    }

    /**
     * Delete a challenge.
     * @param $id
     * @return bool
     */
    public function deleteChanllengesParticipation($id){
        $sql = 'DELETE FROM ParticipationC WHERE idParticipation = ?';

        $this->getDatabase()->execute($sql,$id);

        return true;
    }

} 