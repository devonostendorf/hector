<?php
	echo Form::open('User/create_acct_prcs', array('name' => 'frmCreateAcct', 'id' => 'id_frmCreateAcct'));
?>
				<fieldset>
<?php								
	echo '<legend>'.$page_description.'</legend>';
	echo '<h5>Please select a Username and enter your Email Address</h5>';
	echo Form::label('txtUsername', 'Username:');
	echo Form::input('txtUsername', $post ? $post['txtUsername']: '', array('id' => 'id_txtUsername', 'size' => '35', 'maxlength' => '100', 'required' => true));
	if (array_key_exists('txtUsername', $errors))
	{
		echo '<small class="error">'.$errors['txtUsername'].'</small>';
	}
	echo Form::label('emlEmailAddr', 'Email Address:');
	echo Form::input('emlEmailAddr', $post ? $post['emlEmailAddr']: '', array('id' => 'id_emlEmailAddr', 'size' => '35', 'maxlength' => '100', 'required' => true));
	echo '<br />';
	if (array_key_exists('emlEmailAddr', $errors))
	{
		echo '<small class="error">'.$errors['emlEmailAddr'].'</small>';
	}
	echo Form::button('btnCreateAcct', 'Create!', array('class' => 'small button radius submit'));
?>
				</fieldset>
<?php
	echo Form::close();
?>
