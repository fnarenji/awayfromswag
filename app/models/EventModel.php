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

        return DatabaseProvider::connection()->query($sql);

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

        return DatabaseProvider::connection()->selectFirst($sql, [$id]);

    }

    /**
     * @param $params
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function insertEvent($params)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();

            $sql = <<<SQL
INSERT INTO event (name, user, description, address, eventtime, money, personsmax)
        VALUES (:name, :user, :description, :address, :eventtime, :money, :personsmax);
SQL;
            DatabaseProvider::connection()->execute($sql, $params);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * @param $params
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateEvent($params)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();

            $sql = 'UPDATE event '
                . 'SET name=:name ,description=:description, address=:address, eventtime=:eventtime, money=:money, personsmax=:personsmax '
                . 'WHERE id=:id';

            DatabaseProvider::connection()->execute($sql, $params);

            DatabaseProvider::connection()->commit();

            return true;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * To delete an event.
     * @param $id
     * @return bool
     */
    public function deleteEvent($id)
    {
        $sql = "DELETE FROM event WHERE id = ?";

        DatabaseProvider::connection()->query($sql, [$id]);

        return true;
    }

    /**
     * get if the user participate at the event $id
     * @param $id string event id
     * @param $userId string user id
     */
    public function getParticipateUser($id, $userId)
    {
        //TODO
    }

    /**
     * create participation of the user $userId at the event $id
     * @param $id string event id
     * @param $userId string user
     */
    public function participate($id, $userId)
    {
        //TODO
    }

    /**
     * remove participation of the user $userId at the event $id
     * @param $id string event id
     * @param $userId string user id
     */
    public function unparticipate($id, $userId)
    {
        //TODO
    }
}