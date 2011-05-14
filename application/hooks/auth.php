<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Auth {

    private $CI;

    public function __construct() {
        $this->CI = get_instance();
        $this->CI->load->library('session');
    }

    public function check() {
        if (!$this->CI->session->userdata('logged_in')
                && $this->CI->router->class != 'user'
                && $this->CI->router->method != 'login'
                && $this->CI->router->method != 'loginPost'
        ) {
            redirect('/user/login');
        }
    }

}
