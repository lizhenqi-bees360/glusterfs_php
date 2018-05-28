<?php
	 $username_error = ( trim(form_error('username')) != '' ) ? ' error' : '';
	 $password_error = ( trim(form_error('password')) != '' ) ? ' error' : '';
	 $email_error = ( trim(form_error('email')) != '' ) ? ' error' : '';
	 $cpassword_error = ( trim(form_error('cpassword')) != '' ) ? ' error' : '';
	 $role_error = ( trim(form_error('role')) != '' ) ? ' error' : '';
?>
<?php $this->load->view('template/v_header.php'); ?>
					<div>
<form class="form-horizontal" method="post" action="<?php echo site_url('register'); ?>">
  <fieldset>
    <legend>Register</legend>
			<div>&nbsp;</div>
			
	<?php echo (isset($register_error)) ? "<div class=\"alert alert-error\"><button class=\"close\" data-dismiss=\"alert\">&times;</button><strong>$register_error</strong></div>" : ''; ?>
	<?php echo (isset($register_success)) ? "<div class=\"alert alert-success\"><button class=\"close\" data-dismiss=\"alert\">&times;</button><strong>$register_success</strong></div>" : ''; ?>
    
	<?php if (!isset($register_success)) { ?>
	<div class="control-group<?php echo $username_error; ?>">
      <label class="control-label" for="username">Username</label>
      <div class="controls">
        <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username'); ?>">
    <?php echo form_error('username', '<p class="help-inline">', '</p>'); ?>
      </div>
    </div>
    <div class="control-group<?php echo $role_error; ?>">
      <label class="control-label" for="role">User Role</label>
      <div class="controls">
        <select class="form-control" id="role" name="role">
          <option value="1">集群管理员</option>
          <option value="2">设备管理员</option>
          <option value="3">卷管理员</option>
          <option value="4">普通用户</option>
        </select>
        <!-- <input type="text" class="form-control" id="role" name="role" value="<?php echo set_value('role'); ?>"> -->
		<?php echo form_error('role', '<p class="help-inline">', '</p>'); ?>
      </div>
    </div>
    <div class="control-group<?php echo $email_error; ?>">
      <label class="control-label" for="email">Email</label>
      <div class="controls">
        <input type="text" class="form-control" id="email" name="email" value="<?php echo set_value('email'); ?>">
		<?php echo form_error('email', '<p class="help-inline">', '</p>'); ?>
      </div>
    </div>
    <div class="control-group<?php echo $password_error; ?>">
      <label class="control-label" for="password">Password</label>
      <div class="controls">
        <input type="password" class="form-control" id="password" name="password" value="<?php echo set_value('password'); ?>">
		<?php echo form_error('password', '<p class="help-inline">', '</p>'); ?>
      </div>
	</div>
    <div class="control-group<?php echo $cpassword_error; ?>">
      <label class="control-label" for="cpassword">Confirm Password</label>
      <div class="controls">
        <input type="password" class="form-control" id="cpassword" name="cpassword" value="<?php echo set_value('cpassword'); ?>">
		<?php echo form_error('cpassword', '<p class="help-inline">', '</p>'); ?>
      </div>    
	</div>	
    <div class="control-group">
      <label class="control-label" for="register"> </label>
      <div class="controls">
		<input style="float:right;" type="submit" value="Register" class="btn btn-primary">
      </div>
    </div>

		<?php } ?>
	  </fieldset>
</form>

<p style="margin-top:20px;"><a href="<?php echo site_url('account'); ?>">帐号列表</a></p>

</div>
					
<?php $this->load->view('template/v_footer.php'); ?>
