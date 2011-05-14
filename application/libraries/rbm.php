<?php

class Rbm extends CI_Model {

    protected $_bean, $_table;

    public function __construct($import = null) {
        $class = $this->_table = substr(get_class($this), 2);
        $this->_bean = R::dispense($class);
        if ($import) {
            $this->_bean->import($import);
        }
        parent::__construct();
        $user = R::findOne('user', 'username=?', array('s3v3n'));
        var_dump($user);
    }

    public function getBean() {
        return $this->_bean;
    }

    public function save() {
        if ($this->_bean) {
            return R::store($this->_bean);
        }
        return null;
    }

}