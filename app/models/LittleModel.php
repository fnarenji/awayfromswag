<?php
/**
 * Created by PhpStorm.
 * User: loic
 * Date: 24/01/15
 * Time: 23:00
 */

namespace app\models;

use SwagFramework\Database\DatabaseProvider;
use SwagFramework\mvc\Model;

class LittleModel extends Model
{

    /**
     * Check cpt connect + Add
     * @return mixed
     * @throws \SwagFramework\Exceptions\DatabaseConfigurationNotLoadedException
     */
    public function checkConnect()
    {
        $sql = 'SELECT COUNT(*) AS COUNT FROM cpt_connectes WHERE ip=\'' . $_SERVER['REMOTE_ADDR'] . '\'';
        $data = DatabaseProvider::connection()->query($sql);
        
        if ($data[0]['COUNT'] == 0) {
            $sql = 'INSERT INTO cpt_connectes VALUES(\'' . $_SERVER['REMOTE_ADDR'] . '\', ' . time() . ')';
            DatabaseProvider::connection()->execute($sql);
        } else {
            $sql = 'UPDATE cpt_connectes SET timestamp=' . time() . ' WHERE ip=\'' . $_SERVER['REMOTE_ADDR'] . '\'';
            DatabaseProvider::connection()->execute($sql);
        }

        $timestamp_5min = time() - (60 * 5);
        $sql = 'DELETE FROM cpt_connectes WHERE timestamp < ' . $timestamp_5min;

        $sql = 'SELECT COUNT(*) FROM cpt_connectes';
        $data2 = DatabaseProvider::connection()->query($sql);
        return $data2[0];
    }

}