<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 10/01/15
 * Time: 16:34
 */

namespace app\models;

use SwagFramework\Database\DatabaseProvider;
use SwagFramework\mvc\Model;

class EventModel extends Model
{
    /**
     * Return all event
     * @return array
     */
    public function getEvents()
    {
        $sql = "SELECT nameEvent,userName FROM event,Users WHERE creator = idUsers;";

        return DatabaseProvider::connection()->execute($sql, null);

    }

    /**
     * Return the event
     * @param $id
     * @return array
     */
    public function getOneEvents($id)
    {
        $sql = "SELECT nameEvent,userName,nbParticipantMax FROM event,Users WHERE creator = idUsers AND idEvent = ?;";

        return DatabaseProvider::connection()->execute($sql, $id);

    }

    /**
     * Insert a new event
     * @param $name
     * @param $idc
     * @param $nbMaxPart
     * @return bool
     */
    public function insertEvent($name, $idc, $nbMaxPart)
    {
        $sql = "INSERT INTO event (`nameEvent`,`creator`,`nbParticipantMax`) VALUE ?,?,?";

        DatabaseProvider::connection()->execute($sql, $name, $idc, $nbMaxPart);

        return true;

    }

    /**
     * To delete an event.
     * @param $id
     * @return bool
     */
    public function deleteEvent($id)
    {

        $sql = "DELETE FROM event WHERE idEvent = ?";

        DatabaseProvider::connection()->execute($sql, $id);

        return true;
    }
}