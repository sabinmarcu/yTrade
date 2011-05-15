<?php include '_header.php' ?>
<section>
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if(isset($error)): ?>
<p class="error"><?php echo $error; ?></p>
<?php endif; ?>
<div>

<?php echo form_open('/user/loginPost'); ?>

Username:<br/>
<?php echo form_input(array('name' => 'username')); ?><br/>
Password:<br/>
<?php echo form_password(array('name' => 'password')); ?><br/>
<?php echo form_submit(array('name' => 'submit', 'value'=> 'login')); ?><br/>
<?php echo form_close(); ?>
</div>
<p>
    Don't have an account? Create one <?php echo anchor('/user/register', 'here'); ?>!
</p>
</section>
<?php include '_footer.php' ?>