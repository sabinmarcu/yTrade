<?php
class chart extends CI_Controller {
    public function index () {
        // get i: interval in minutes
        // get n: 
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
        
        echo json_encode($r);
    }
}