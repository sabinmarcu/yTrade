<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    public function login() {
//        $this->session->set_userdata('logged_in', 1);
//        redirect('/');
        $this->load->view('login');
    }

    public function loginPost() {

        try {

            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // check that fields are present
            if (in_array(null, array($username, $password))) {
                throw new Exception('Incorect username or password');
            }

            $hash = sha256($password);
            $user = R::findOne(
                            'user',
                            'username=? and hash=?',
                            array($username, $hash)
            );

            if ($user == false) {
                throw new Exception('Incorect username or password');
            }

            $this->session->set_userdata('logged_in', 1);
            $this->session->set_userdata('username', $user->username);
            redirect('welcome');
        } catch (Exception $error) {
            $this->load->view('login.php',
                    array('error' => $error->getMessage())
            );
        }
    }

    public function register() {
        $this->load->view('register');
    }

    public function registerPost() {

        try {

            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $password2 = $this->input->post('password2');

            // check that fields are present
            if (in_array(null, array($username, $password, $password2))) {
                throw new Exception('All fields are required!');
            }

            if ($password != $password2) {
                throw new Exception('Passwords don\'t match!');
            }

            $existent = R::findOne('user', 'username=?', array($username));

            if ($existent) {
                throw new Exception('Username already in use!');
            }

            $hash = sha256($password);
            $new_user = R::dispense('user');
            $new_user->username = $username;
            $new_user->hash = sha256($password);
            R::store($new_user);

            $this->session->set_userdata('logged_in', 1);
            $this->session->set_userdata('username', $username);
            redirect('welcome');
        } catch (Exception $error) {
            $this->load->view('register.php',
                    array('error' => $error->getMessage())
            );
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('/user/login');
    }

}