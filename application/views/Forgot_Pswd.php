<?php
	echo Form::open('User/forgot_pswd_prcs', array('name' => 'frmForgotPswd', 'id' => 'id_frmForgotPswd', 'class' => 'custom'));
?>
				<fieldset>
<?php								
	echo '<legend>'.$page_description.'</legend>';
	echo '<h5>Please enter your Username or Email Address</h5>';
	echo Form::label('txtUsernameOrEmailAddr', 'Username or Email Address:');
	echo Form::input('txtUsernameOrEmailAddr', $post ? $post['txtUsernameOrEmailAddr']: '', array('id' => 'id_txtUsernameOrEmailAddr', 'size' => '35', 'maxlength' => '100', 'required' => true));
	if (array_key_exists('txtUsernameOrEmailAddr', $errors))
	{
		echo '<small class="error">'.$errors['txtUsernameOrEmailAddr'].'</small>';
	}
	echo '<br />';
	echo Form::button('btnResetPswd', 'Reset Password', array('class' => 'small button radius submit'));
?>
				</fieldset>
<?php
	echo Form::close();
?>
