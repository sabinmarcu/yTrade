<?php include '_header.php' ?>
<section>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if(isset($error)): ?>
<p class="error"><?php echo $error; ?></p>
<?php endif; ?>

<?php echo form_open('/user/registerPost'); ?>

Desired username:<br/>
<?php echo form_input(array('name' => 'username')); ?><br/>
Password:<br/>
<?php echo form_password(array('name' => 'password')); ?><br/>
Repeat password:<br/>
<?php echo form_password(array('name' => 'password2')); ?><br/>

<?php echo form_submit(array('name' => 'submit', 'value'=> 'register')); ?><br/>
<?php echo form_close(); ?>

</section>
<?php include '_footer.php' ?>