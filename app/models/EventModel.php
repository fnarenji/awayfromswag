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
     * Get all event
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getEvents()
    {
        $sql = "SELECT id, name, username, description, address, eventtime, money, personsmax, personsnow " .
            "FROM event,user " .
            "WHERE event.user = user.id ;";

        return DatabaseProvider::connection()->execute($sql, null);

    }

    /**
     * Get one event
     * @param $id
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getOneEvents($id)
    {
        $sql = "SELECT name, username, description, address, eventtime, money, personsmax, personsnow " .
            "FROM event,user " .
            "WHERE event.user = user.id AND user.id = ?;";

        return DatabaseProvider::connection()->execute($sql, $id);

    }

    /**
     * Insert in DB a new event
     * @param $name
     * @param $idCreator
     * @param $nbMaxPart
     * @param $description
     * @param $address
     * @param $eventTime
     * @param $money
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function insertEvent($name, $idCreator, $nbMaxPart, $description, $address, $eventTime, $money)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();

            $sql = "INSERT INTO event ('name','user','description', 'address', 'eventtime', 'money', 'personsmax') " .
                " VALUE ?,?,?,?,?,?,?";

            DatabaseProvider::connection()->execute($sql, $name, $idCreator, $description, $address, $eventTime, $money,
                $nbMaxPart);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {

            DatabaseProvider::connection()->rollBack();
        }

        return false;
    }

    /**
     * Update an event
     * @param $idEvent
     * @param $name
     * @param $nbMaxPart
     * @param $description
     * @param $address
     * @param $eventTime
     * @param $money
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function upadateEvent($idEvent, $name, $nbMaxPart, $description, $address, $eventTime, $money)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();

            $sql = "UPDATE event " .
                "SET name = ? ,description = ?, address = ?, eventtime = ?, money = ?, personsmax = ? " .
                "WHERE id = ?";

            DatabaseProvider::connection()->execute($sql, $name, $description, $address, $eventTime, $money, $nbMaxPart,
                $idEvent);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {

            DatabaseProvider::connection()->rollBack();
        }

        return false;
    }

    /**
     * To delete an event.
     * @param $id
     * @return bool
     */
    public function deleteEvent($id)
    {
        $sql = "DELETE FROM event WHERE id = ?";

        DatabaseProvider::connection()->execute($sql, $id);

        return true;
    }
}