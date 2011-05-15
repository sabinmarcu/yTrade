<?php

class history extends CI_Controller {
    public function index () {
        $userid = $this->session->userdata('userid');
        $history = R::find('sale', 'userid=? ORDER BY acceptedat DESC, createdat DESC', array($userid));
        var_dump($history);
    }
}
