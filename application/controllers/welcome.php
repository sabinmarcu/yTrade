<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index() {
        $user = R::dispense('user');
        $user->username = 's3v3n';
        $user->hash = md5('asdf1234');
        R::store($user);
        $this->load->view('welcome_message', array());
    }

}
