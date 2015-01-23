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
    public function getAll()
    {
        $sql = "SELECT * " .
            "FROM event ";

        return DatabaseProvider::connection()->execute($sql, []);

    }

    /**
     * Get one event
     * @param $id
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function get($id)
    {
        $sql = "SELECT * " .
            "FROM event " .
            "WHERE id=?";

        return DatabaseProvider::connection()->execute($sql, [$id]);

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

            DatabaseProvider::connection()->execute($sql, [$name, $idCreator, $description, $address, $eventTime, $money, $nbMaxPart]);

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
    public function updateEvent($idEvent, $name, $nbMaxPart, $description, $address, $eventTime, $money)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();

            $sql = "UPDATE event " .
                "SET name = ? ,description = ?, address = ?, eventtime = ?, money = ?, personsmax = ? " .
                "WHERE id = ?";

            DatabaseProvider::connection()->execute($sql, [$name, $description, $address, $eventTime, $money, $nbMaxPart, $idEvent]);

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

        DatabaseProvider::connection()->execute($sql, [$id]);

        return true;
    }
}