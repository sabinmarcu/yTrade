<?php

class User extends CI_Controller {

    public function login(){
        $this->load->view('login', array('action' => site_url('/user/loginPost')));
    }

    public function loginPost(){
        $this->session->set_userdata('logged_in', 1);
        redirect('welcome');
    }

}