<?php
	echo Form::open('User/sign_in_prcs', array('name' => 'frmSignIn', 'id' => 'id_frmSignIn'));
?>
				<fieldset>
<?php								
	echo '<legend>'.$page_description.'</legend>';
	echo Form::label('txtUsernameOrEmailAddr', 'Username or Email Address:');
	echo Form::input('txtUsernameOrEmailAddr', $post ? $post['txtUsernameOrEmailAddr']: '', array('id' => 'id_txtUsernameOrEmailAddr', 'size' => '35', 'maxlength' => '100', 'required' => true));
	if (array_key_exists('txtUsernameOrEmailAddr', $errors))
	{
		echo '<small class="error">'.$errors['txtUsernameOrEmailAddr'].'</small>';
	}
	echo Form::label('pwdPswd', 'Password:');
	echo Form::password('pwdPswd', NULL, array('id' => 'id_pwdPswd', 'size' => '35', 'maxlength' => '200', 'required' => true));
	if (array_key_exists('pwdPswd', $errors))
	{
		echo '<small class="error">'.$errors['pwdPswd'].'</small>';
	}
	echo '<br />';
	echo Form::button('btnSignIn', 'Sign In', array('class' => 'small button radius submit'));
	echo '<br />'.HTML::anchor('User/forgot_pswd', 'Forgot password?');
?>
				</fieldset>
<?php
	echo '<br />'.HTML::anchor('User/create_acct', 'Create an account');
	echo Form::close();
?>
