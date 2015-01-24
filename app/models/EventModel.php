<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 10/01/15
 * Time: 16:34
 */

namespace app\models;

use SwagFramework\Database\DatabaseProvider;
use SwagFramework\Helpers\Authentication;
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

            $state = DatabaseProvider::connection()->execute($sql, $params);

            DatabaseProvider::connection()->commit();

            return $state;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * Update an event
     * @param $params
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateEventById($params)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();


            $str = '';

            foreach($params as $key=>$value){
                if($key != 'id'){
                    $str .= ''.$key.' = \''.$value.'\' ,';
                }
            }

            $str  = substr($str, 0, -1);

            $sql = 'UPDATE event '
                . 'SET '. $str . ' WHERE id = ?';

            var_dump($sql);
            var_dump($params);

            $state = DatabaseProvider::connection()->execute($sql, [$params['id']]);

            DatabaseProvider::connection()->commit();

            return $state;

        } catch (\Exception $e) {
            DatabaseProvider::connection()->rollBack();
            throw $e;
        }
    }

    /**
     * Update Event by name
     * @param $params
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateEventByName($params)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();


            $str = '';

            foreach($params as $key=>$value){
                if($key != 'id'){
                    $str .= ''.$key.' = \''.$value.'\' ,';
                }
            }

            $str  = substr($str, 0, -1);

            $sql = 'UPDATE event '
                . 'SET '. $str . ' WHERE name = ?';

            var_dump($sql);
            var_dump($params);

            $state = DatabaseProvider::connection()->execute($sql, [$params['name']]);

            DatabaseProvider::connection()->commit();

            return $state;

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
    public function deleteEventId($id)
    {
        $sql = "DELETE FROM event WHERE id = ?";

        DatabaseProvider::connection()->execute($sql, [$id]);

        return true;
    }

    /**
     * Delete event by name
     * @param $name
     * @return bool
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function deleteEventName($name)
    {
        $sql = "DELETE FROM event WHERE name = ?";

        return DatabaseProvider::connection()->execute($sql, [$name]);
    }

    /**
     * get if the user participate at the event $id
     * @param $id string event id
     * @param $userId string user id
     */
    public function getParticipateUser($id, $userId)
    {
        $sql = "SELECT * FROM event_user WHERE event_user.id = ? AND event_user.user = ? ;";

        return DatabaseProvider::connection()->query($sql,[$id,$userId]);
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

    /**
     * get events with max participation
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getTop()
    {
        $sql = <<<SQL
SELECT * FROM event
        JOIN event_user ON event.id = event_user.id
        LIMIT 3;
SQL;

        DatabaseProvider::connection()->query($sql);
    }

    /**
     * get last events
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getLast()
    {
        $sql = <<<SQL
SELECT * FROM event
  ORDER BY createtime DESC
  LIMIT 3;
SQL;

        return DatabaseProvider::connection()->query($sql);

    }

    /**
     * @param int $year
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getEventsByYear($year = 2015)
    {
        $sql = 'SELECT event.id, name, eventtime FROM event ' .
               'LEFT JOIN event_user ' .
               'ON event_user.id = event.id '.
               'WHERE event.user = ? ' .
               'AND YEAR(eventtime) = ? ' .
               'OR event_user.user = ? ' .
               'AND YEAR(eventtime)= ? ';

        $user = Authentication::getInstance()->getUserId();

        return DatabaseProvider::connection()->query($sql, array($user, $year, $user, $year));
    }

    public function count()
    {
        $sql = <<<SQL
SELECT COUNT(id) AS nb FROM event;
SQL;

        return DatabaseProvider::connection()->selectFirst($sql, []);
    }
}