<?php

include "_header.php";

echo form_open('/user/loginPost');

echo 'username:';
echo form_input(array('name' => 'username'));
echo '<br/>password:';
echo form_password(array('name' => 'password'));
echo form_submit(array('name' => 'submit', 'value'=> 'login'));

echo form_close();

include "_footer.php";