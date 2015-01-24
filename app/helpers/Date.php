<?php
/**
 * Created by PhpStorm.
 * User: thomasmunoz
 * Date: 24/01/15
 * Time: 18:17
 */

namespace app\helpers;

use app\models\EventModel;

class Date{

    var $days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi','Dimanche');
    var $months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                            'Septembre', 'Octobre', 'Novembre', 'Décembre');

    function getEvents($year){
        $model = new EventModel();
        $result = $model->getEventsByYear($year);

        $r = array();

        /*while($d = $req->fetch(PDO::FETCH_OBJ)){
            $r[strtotime($d->date)][$d->id] = $d->title;
        }*/

        foreach($result as $key => $value){
            $day = strtotime(substr($value['eventtime'], 0, 10))/* + 240800*/;
            $r[$day][$value['id']] = $value['name'];
        }

        return $r;
    }

    function getAll($year){
        $r = array();
        /**
         * Boucle version procédurale
         *
        $date = strtotime($year.'-01-01');
        while(date('Y',$date) <= $year){
        // Ce que je veux => $r[ANEEE][MOIS][JOUR] = JOUR DE LA SEMAINE
        $y = date('Y',$date);
        $m = date('n',$date);
        $d = date('j',$date);
        $w = str_replace('0','7',date('w',$date));
        $r[$y][$m][$d] = $w;
        $date = strtotime(date('Y-m-d',$date).' +1 DAY');
        }
         *
         *
         */
        $date = new \DateTime($year.'-01-01');
        while($date->format('Y') <= $year){
            // Ce que je veux => $r[ANEEE][MOIS][JOUR] = JOUR DE LA SEMAINE
            $y = $date->format('Y');
            $m = $date->format('n');
            $d = $date->format('j');
            $w = str_replace('0','7',$date->format('w'));
            $r[$y][$m][$d] = $w;
            $date->add(new \DateInterval('P1D'));
        }
        return $r;
    }

}