<?php
	echo Form::open($submit_handler, array('name' => 'frmSetPswd', 'id' => 'id_frmSetPswd'));
?>
				<fieldset>
<?php								
	echo '<legend>'.$page_description.'</legend>';
	echo Form::label('NA', 'Username: '.Session::instance()->get('username')).'<br />';
	echo Form::label('pwdNewPassword', 'New Password:');
	echo Form::password('pwdNewPassword', NULL, array('id' => 'id_pwdNewPassword', 'size' => '35', 'maxlength' => '30'));
	if (array_key_exists('pwdNewPassword', $errors))
	{
		echo '<small class="error">'.$errors['pwdNewPassword'].'</small>';
	}
	echo Form::label('pwdConfPassword', 'Confirm Password:');
	echo Form::password('pwdConfPassword', NULL, array('id' => 'id_pwdConfPassword', 'size' => '35', 'maxlength' => '30'));
	if (array_key_exists('pwdConfPassword', $errors))
	{
		echo '<small class="error">'.$errors['pwdConfPassword'].'</small>';
	}
	echo '<br />';
	echo Form::button('btnUpdate', 'Update', array('class' => 'small button radius submit'));
?>
				</fieldset>
<?php
	echo Form::close();
?>
