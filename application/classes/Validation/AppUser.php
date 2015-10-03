<?php defined('SYSPATH') or die('No direct script access.');

class Validation_AppUser extends Validation {

	public static function create_acct($post)
	{
			  
		/*
		/	Purpose: Validate Create Acct form submitted data
		/
		/	Parms:
		/		Array containing:
		/			'txtUsername' >> New user's username
		/			'emlEmailAddr' >> New user's email address
		/
		/	Returns:
		/		Array containing:
		/			'Clean_Post_Data' >> Array containing cleaned form data
		/			'Success' >> Boolean indicating success/failure of validation
		/		[AND]
		/			'Errors' >> Array containing form validation errors
		*/
		
		$return_arr = array(
			'Success' => FALSE		  
		);
			  
		$clean_post_data = Validation::factory($post)			
			
			// Username must be non-empty
			->rule('txtUsername', 'not_empty')											
			
			// Username must not be entirely whitespace chars
			->rule('txtUsername', 'Model_AppUser::non_blank')
			
			// Username must not be more than 100 chars long
			->rule('txtUsername', 'max_length', array(':value', 100))									
			
			// Username must not already exist in system		
			->rule('txtUsername', 'Model_AppUser::unique_username')
			
			// Email address must not be empty
			->rule('emlEmailAddr', 'not_empty')		

			// Email address must be valid
			->rule('emlEmailAddr', 'email')			
			
			// Email address must not be more than 100 chars long
			->rule('emlEmailAddr', 'max_length', array(':value', 100))									

			// Email address must not already exist in system		
			->rule('emlEmailAddr', 'Model_AppUser::unique_emailaddr')			
		;
		
		if ($clean_post_data->check())
		{
			$return_arr['Clean_Post_Data'] = $clean_post_data;
			
			$return_arr['Success'] = TRUE;
		}
		else
		{	
			$return_arr['Errors'] = $clean_post_data->errors('form_errors');
		}
		
		return $return_arr;
		
	}
	
	public static function forgot_password($post)
	{
			  
		/*
		/	Purpose: Validate Forgot Password form submitted data
		/
		/	Parms:
		/		Array containing:
		/			'txtUsernameOrEmailAddr' >> User's username or email address 
		/
		/	Returns:
		/		Array containing:
		/			'Clean_Post_Data' >> Array containing cleaned form data
		/			'Success' >> Boolean indicating success/failure of validation
		/		[AND]
		/			'Errors' >> Array containing form validation errors
		*/

		$return_arr = array(
			'Success' => FALSE		  
		);
			  
		$clean_post_data = Validation::factory($post)			
			
			// Username or email address must be non-empty
			->rule('txtUsernameOrEmailAddr', 'not_empty')			

			// Username or email address must not be entirely whitespace chars
			->rule('txtUsernameOrEmailAddr', 'Model_AppUser::non_blank')
		;
		
		if ($clean_post_data->check())
		{
			$return_arr['Clean_Post_Data'] = $clean_post_data;
			
			$return_arr['Success'] = TRUE;
		}
		else
		{	
			$return_arr['Errors'] = $clean_post_data->errors('form_errors');
		}
		
		return $return_arr;
		
	}	
	
	public static function set_password($post)
	{
			  
		/*
		/	Purpose: Validate Set Password form submitted data
		/
		/	Parms:
		/		Array containing:
		/			'pwdNewPassword' >> User's new password
		/			'pwdConfPassword' >> User's new password (confirmation)
		/
		/	Returns:
		/		Array containing:
		/			'Clean_Post_Data' >> Array containing cleaned form data
		/			'Success' >> Boolean indicating success/failure of validation
		/		[AND]
		/			'Errors' >> Array containing form validation errors
		*/

		$return_arr = array(
			'Success' => FALSE		  
		);
			  
		$clean_post_data = Validation::factory($post)			

			// Confirm password must match new password
			->rule('pwdConfPassword', 'matches', array(':validation', ':field', 'pwdNewPassword'))		
		;
		
		if ($clean_post_data->check())
		{
			$return_arr['Clean_Post_Data'] = $clean_post_data;
			
			$return_arr['Success'] = TRUE;
		}
		else
		{	
			$return_arr['Errors'] = $clean_post_data->errors('form_errors');
		}
		
		return $return_arr;
		
	}

	public static function sign_in($post)
	{
			  
		/*
		/	Purpose: Validate Sign In form submitted data
		/
		/	Parms:
		/		Array containing:
		/			'txtUsernameOrEmailAddr' >> User's username or email address 
		/			'pwdPswd' >> User's password
		/
		/	Returns:
		/		Array containing:
		/			'Clean_Post_Data' >> Array containing cleaned form data
		/			'Success' >> Boolean indicating success/failure of validation
		/		[AND]
		/			'Errors' >> Array containing form validation errors
		*/

		$return_arr = array(
			'Success' => FALSE		  
		);
			  
		$clean_post_data = Validation::factory($post)			
			
			// Username or email address must not be empty
			->rule('txtUsernameOrEmailAddr', 'not_empty')			
				
			// Password must not be empty
			->rule('pwdPswd', 'not_empty')		

			// Password must match what is in DB
			->rule('pwdPswd', 'Model_AppUser::valid_curr_pswd', array($post['txtUsernameOrEmailAddr'], $post['pwdPswd']))
		;
		
		if ($clean_post_data->check())
		{
			$return_arr['Clean_Post_Data'] = $clean_post_data;
			
			$return_arr['Success'] = TRUE;
		}
		else
		{	
			$return_arr['Errors'] = $clean_post_data->errors('form_errors');
		}
		
		return $return_arr;
		
	}

	public static function validate_authcode($data)
	{
		
		/*
		/	Purpose: Validate authcode and email address passed in via query string
		/
		/	Parms:
		/		Array containing:
		/			'email_addr' >> User's email address
		/			'authcode' >> User's authcode
		/
		/	Returns:
		/		Boolean indicating success/failure of validation
		*/
		
		$clean_get_data = Validation::factory($data)			
			
			// Email address must not be empty
			->rule('email_addr', 'not_empty')		

			// Email address must be valid
			->rule('email_addr', 'email')			
			
			// Email address must not be more than 100 chars long
			->rule('email_addr', 'max_length', array(':value', 100))									
				
			// Authcode must be 32 chars long
			->rule('authcode', 'exact_length', array(':value', 32))	
			
			// Authcode must consist of only alphanumeric chars
			->rule('authcode', 'alpha_numeric')	
			
			// Authcode must match what is in DB
			->rule('authcode', 'Model_AppUser::valid_curr_authcode', array($data['email_addr'], $data['authcode']))
			
		;
		
		if ($clean_get_data->check())
		{
			
			return TRUE;
		}
		
		return FALSE;
		
	}
	
} // End Validation_AppUser
