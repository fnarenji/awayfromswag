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
    public function getAll($start = 0, $end = 10)
    {
        $sql = <<<SQL
SELECT * FROM event
  ORDER BY id DESC
  LIMIT $start, $end;
SQL;

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
INSERT INTO event (name, user, description, address, eventtime, money, personsmax, image)
        VALUES (:name, :user, :description, :address, :eventtime, :money, :personsmax, :image);
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
     * @param $params
     * @return bool
     * @throws \Exception
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function updateEventById($params)
    {
        try {

            DatabaseProvider::connection()->beginTransaction();


            $str = '';

            foreach($params as $key=>$value){
                if($key != 'id'){
                    $str .= '' . $key . ' = :' . $key . ', ';
                }
            }

            $str = substr($str, 0, -2);

            $sql = 'UPDATE event '
                . 'SET ' . $str . ' WHERE id = :id';

            $state = DatabaseProvider::connection()->execute($sql, $params);

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
                    $str .= '' . $key . ' = \':' . $key . '\' ,';
                }
            }

            $str  = substr($str, 0, -1);

            $sql = 'UPDATE event '
                . 'SET ' . $str . ' WHERE name = :name';

            var_dump($sql);
            var_dump($params);

            $state = DatabaseProvider::connection()->execute($sql, $params);

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
        $sql = 'DELETE FROM event WHERE id = ' . $id;

        DatabaseProvider::connection()->execute($sql);

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
     * @param $id
     * @param $userId
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getParticipateUser($id, $userId)
    {
        $sql = "SELECT * FROM event_user WHERE event_user.id = ? AND event_user.user = ? ;";

        return DatabaseProvider::connection()->query($sql,[$id,$userId]);
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
 GROUP BY event.id
 ORDER BY personsnow DESC
 LIMIT 3
SQL;


        return DatabaseProvider::connection()->query($sql);
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
     * Get the last events of an user (with min and max)
     * @param $id
     * @param int $min
     * @param int $max
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getEventsForUser($id, $min = 0, $max = 5)
    {
        $sql = 'SELECT event.id, name, eventtime FROM event ' .
            'LEFT JOIN event_user ' .
            'ON event_user.id = event.id '.
            'WHERE event.user = ? ' .
            'OR event_user.user = ? ' .
            'ORDER BY event.id ' .
            'LIMIT ' . $min . ','. $max . '';

        return DatabaseProvider::connection()->query($sql, array($id, $id));
    }
    /**
     * @param int $year
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getEventsByYear($year)
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

    /**
     * Returns the creator or an event
     * @param $id
     * @return array
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function getCreatorId($id)
    {
        $sql = 'SELECT user FROM event WHERE id = ?';

        return DatabaseProvider::connection()->query($sql, array($id));
    }

    public function count()
    {
        $sql = <<<SQL
SELECT COUNT(id) AS nb FROM event;
SQL;

        return DatabaseProvider::connection()->selectFirst($sql, []);
    }

    public function search($query)
    {
        $sql = <<<SQL
SELECT event.*
FROM event
JOIN user ON event.user = user.id
WHERE MATCH(event.name, event.description, event.address) AGAINST (:query)
   OR MATCH(user.username, user.firstname, user.lastname) AGAINST (:query)
SQL;

        return DatabaseProvider::connection()->query($sql, ['query' => $query]);
    }
}