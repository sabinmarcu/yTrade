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

            $hash = hash("sha256", $password);
            $user = R::findOne(
                            'user', 'username=? and hash=?', array($username, $hash)
            );

            if ($user == false) {
                throw new Exception('Incorect username or password');
            }

            $this->session->set_userdata('logged_in', 1);
            $this->session->set_userdata('username', $user->username);
            $this->session->set_userdata('userid', $user->id);
            redirect('welcome');
        } catch (Exception $error) {
            $this->load->view('login.php', array('error' => $error->getMessage())
            );
        }
    }

    public function register() {
        $currencies = R::find('currency');
        $push = array();
        foreach ($currencies as $c) {
            $push[$c->id] = $c->code . '(' . $c->name . ')';
        }
        $this->load->view('register', array('currencies' => $push));
    }

    public function registerPost() {
        die('x');

        try {

            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $password2 = $this->input->post('password2');
            $currency = $this->input->post('currency');

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

            $hash = hash("sha256", $password);
            $new_user = R::dispense('user');
            $new_user->username = $username;
            $new_user->hash = hash("sha256", $password);
            $id = R::store($new_user);

            // get XAU id (gold)
            $xau = R::findOne('currency', 'code = ?', array('XAU'));
            $xau_id = $xau->id;

            $account = R::dispense('account');
            $account->userid = $id;
            $account->currency = $xau_id;
            $account->amount = 1;
            R::store($account);

            $this->session->set_userdata('logged_in', 1);
            $this->session->set_userdata('username', $username);
            $this->session->set_userdata('userid', $id);
            redirect('welcome');
        } catch (Exception $error) {
            $currencies = R::find('currency');
            $push = array();
            foreach ($currencies as $c) {
                $push[$c->id] = $c->code . '(' . $c->name . ')';
            }
            var_dump($push);
            die();
            $this->load->view('register', array(
                'currencies' => $push,
                'error' => $error->getMessage()
            ));
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('/user/login');
    }

    protected function updateGold() {
        $this->load->helper('getgold');
        $this->load->helper('exchange');
        $userid = $this->session->userdata('userid');
        $gold = getgold($userid);
        $user = R::load('user', $userid);
        $user->gold = $gold;
        if (R::store($user)) {
            return $gold;
        }
        return false;
    }

    public function status() {
        $moneys = R::find('account', 'userid=?', array($this->session->userdata('userid')));
        if (!$moneys)
            echo json_encode(array('error' => 'You have no money'));
        $r = array();
        foreach ($moneys as $cur) {
            $moneda = R::findOne('currency', 'id=?', array($cur->currency));
            $r[$moneda->code] = $cur->amount;
        }
        $r['gold'] = $this->updateGold();
        echo json_encode($r);
    }

}