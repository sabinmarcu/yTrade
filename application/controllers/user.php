<?php

class User extends CI_Controller {

    public function login() {
//        $this->session->set_userdata('logged_in', 1);
//        redirect('/');
        $this->load->view('login');
    }

    public function loginPost() {
        $this->session->set_userdata('logged_in', 1);
        redirect('welcome');
    }

}