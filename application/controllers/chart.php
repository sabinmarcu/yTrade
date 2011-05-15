<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class chart extends CI_Controller {
    const DATE_FORMAT='j M Y h:iA';
    const COUNT = 10;

    public function index() {
        // get ago: interval in minutes
        // get count: how many points in the chart
        // get cur: currencies pair

        $start = $this->input->get('start');
        if (!$start)
            die('check input');

        $return = array();
//        echo "bu!";
//        var_dump($start);
        foreach ($start as $chart => $options) {
            if (!isset($options['cur'], $options['ago'], $options['div'])) {
                die(json_encode(array('error')));
            }
                 
            $return[$chart] = $this->_calculate($options);
            //var_dump($return[$chart]);
        }
//        echo "merge";
        /*
          $max=$this->input->get('n');
          if (!$max) $max = 5;
          $interval = $this->input->get('i');
          if (!$interval) $interval = 5;
          // put interval in seconds
          $interval*=60;
          $now = time()-($interval*$max);
          $r = array();
          for ($i=0;$i<$max;$i++) {
          $r[]=array(
          'time'=>date('h:iA',$now),
          'rate'=>mt_rand(1,1000)/1000,
          );
          $now+=$interval;
          }
         */
        echo json_encode($return);
    }

    protected function _calculate($options) {
        $now = time();
        $start = $now - $options['ago'];
        $end = $start + $options['div'] * self::COUNT;
        $div = $options['div'];
        $step = $start;
        if ($end > $now) {
            $end = $now;
        }
        $first = R::findOne('rate', 'timestamp < ? AND pair=? ORDER BY timestamp DESC', array($start, $options['cur']));
        $beans = R::find('rate', 'timestamp >= ? AND timestamp < ? AND pair = ? ORDER BY timestamp ASC', array($start, $end, $options['cur']));
        if ($first) {
            $rates = array($first);
        } else {
            $rates = array(reset($beans));
        }
        $rates = array_merge($rates, $beans);
        //var_dump($rates); die();
        if (!empty($rates)) {
            $return = array();
            for ($j = 0; $j < self::COUNT; $j++) {
                
                $step +=$div;
                $first = reset($rates);

                //var_dump($first); //die("\n".$step);

                while ($first['timestamp'] < $step) {
                    $first = array_shift($rates);
                    //echo "rates:"; var_dump($rates); die();
                    if (empty($rates)) break;
                }
                
                if (isset($first['rate']) && !empty($first['rate'])) {
                    $lastRate = $first['rate'];
                }
                
                $return[] = array(
                    'time' => date('d-m-Y h:iA'),
                    'rate' => isset($lastRate) ? $lastRate : $first['rate'],
                );
                
            }
            return $return;
        }
        return array(); // else return empty array
    }

//    protected function _calculate($options) {
//        $pair = $options['cur'];
//        $start = time() - $options['ago'];
////        var_dump($options);
//        $interval = round((time() - $start) / $options['count']);
//        $beans = R::find('rate', 'timestamp >= ? AND pair = ? ORDER BY timestamp ASC',
//                array($start, $pair));
//        //var_dump($beans); die();
//        if (empty($beans)) die('empty set');
//        $beansCount = count($beans);
//
//        if ($beansCount <= $options['count']) {
//            $return = array();
//            foreach ($beans as $bean) {
//                $return[]=array(
//                    'time'=>date(self::DATE_FORMAT,$bean['timestamp']),
//                    'rate'=>$bean['rate'],
//                );
//            }
//        }
//        $search = 0;
//        $return = array();
//        $cache = null;
//        foreach ($beans as $bean) {
//            if ($search==0) {
//                $search = $bean['timestamp'];
//                $return[]=array(
//                    'time'=>date(self::DATE_FORMAT,$bean['timestamp']),
//                    'rate'=>$bean['rate'],
//                );
//                $search+=$interval;
//            } elseif ($search >= $bean['timestamp']) {
//                $cache = $bean['timestamp'];
//            }
//            if ($cache != null && $search < $bean['timestamp']) {
//                $search = $bean['timestamp'];
//                $return[]=array(
//                    'time'=>date(self::DATE_FORMAT,$bean['timestamp']),
//                    'rate'=>$bean['rate'],
//                );
//                $search+=$interval;
//                $cache = null;
//            }
//        }
//        return $return;
//    }
}