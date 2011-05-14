<?php

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function check() {
        if (!$this->session->userdata('logged_in')) {
            redirect('/login');
        }
    }

}