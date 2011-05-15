<?php

function getgold ($user) {
    R::find('account','userid=?',array($this->session->userdata('userid')));
}