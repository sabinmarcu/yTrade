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
<<<<<<< HEAD
            redirect('/user/login', 'refresh');
=======
            redirect('/user/login', "refresh");
>>>>>>> e18485b8dca5bf6938b4f2ca0037d34c8c61d877
        }
    }

}
