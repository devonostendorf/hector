<?php defined('SYSPATH') or die('No direct script access.');

class Model_AppUser extends Model_Database {
		  
	public function __construct()  
	{

		/*
		/	Purpose: Create user model
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		parent::__construct();
		$this->table_name = 'app_user';  
		  		
		// Don't specify user_id column, so that next sequence number is applied
		// Don't specify created_dttm column, so that current date/timestamp is applied
		$this->table_cols = array(
			'email_addr'
			,'username'
			,'user_pswd'
			,'user_type'
			,'authcode'
			,'authcode_gen_dttm'
			,'validated'
			,'validated_dttm'
			,'last_signin_dttm'
		); 	
		
	}
	

	
	// SQL functions

	public function create($data)
	{
			  
		/*
		/	Purpose: Create new user
		/
		/	Parms:
		/		Array containing:
		/			'email_addr' >> New user's email address
		/			'username' >> New user's username
		/			'user_pswd' >> New user's (hashed) "garbage" password
		/			'authcode' >> New user's authcode
		/		
		/	Returns:
		/		Array containing:
		/			'Row_Created_ID' >> New user's user_id
		/		AND
		/			'Rows_Affected' >> Number of rows inserted into table
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try
		{
			list($return_arr['Row_Created_ID'], $return_arr['Rows_Affected']) = 
				DB::insert($this->table_name)
					->columns($this->table_cols)
					->values(array(
						$data['email_addr']
						,$data['username']
						,$data['user_pswd']
						
						// Default user_type to standard user (U)
						,'U'
						,$data['authcode']

						// Default authcode_gen_dttm to current datetime
						,date('Y-m-d H:i:s') 

						// Default validated to FALSE
						,FALSE
				
						// Default validated_dttm to zero date
						,'0000-00-00 00:00:00'

						// Default last_signin_dttm to zero date
						,'0000-00-00 00:00:00'
					))
					->execute()
			;
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'AppUser->create()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}

	public function read_user_data($data)
	{
			  
		/*
		/	Purpose: Read user data, based on username
		/
		/	Parms:
		/		Array containing:
		/			'username' >> User's username
		/		
		/	Returns:
		/		Array containing:
		/			'Rows' >> Array of specified user columns
		/		AND
		/			'Rows_Affected' >> Number of user rows in 'Rows'
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/
					
		$return_arr = array();
		
		try
		{
			$return_arr['Rows'] = 
				DB::select(
					'user_id'
					,'email_addr'
					,'username'
					,'user_type'
					,'validated_dttm'
				)
					->from($this->table_name)
					->where('username', '=', $data['username'])
					->execute()
			;			  
			$return_arr['Rows_Affected'] = count($return_arr['Rows']);
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'AppUser->read_user_data()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	
	public function read_user_data_flexible($data)
	{

		/*
		/	Purpose: Read user data, based on username OR email address
		/
		/	Parms:
		/		Array containing:
		/			'username_or_email_addr' >> User's username or email address
		/		
		/	Returns:
		/		Array containing:
		/			'Rows' >> Array of specified user columns
		/		AND
		/			'Rows_Affected' >> Number of user rows in 'Rows'
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/
			  
		$return_arr = array();
		
		try
		{
			$return_arr['Rows'] = 
				DB::select(
					'user_id'
					,'username'
					,'email_addr'
					,'user_type'
				)
					->from($this->table_name)
					->where('username', '=', $data['username_or_email_addr'])
					->or_where('email_addr', '=', $data['username_or_email_addr'])
					->execute()
			;		
			$return_arr['Rows_Affected'] = count($return_arr['Rows']);
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'AppUser->read_user_data_flexible()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	
	public function read_username_w_email_addr($data)
	{

		/*
		/	Purpose: Read user's username, based on email address
		/
		/	Parms:
		/		Array containing:
		/			'email_addr' >> User's email address
		/		
		/	Returns:
		/		Array containing:
		/			'Column' >> Specified user's username
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try
		{
			$return_arr['Column'] = 
				DB::select('username')
					->from($this->table_name)
					->where('email_addr', '=', $data['email_addr'])
					->execute()
					->get('username')
			;  
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'AppUser->read_username_w_email_addr()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
		
	public function update_user_authcode($data)
	{
			  
		/*
		/	Purpose: Set specific user's authcode and authcode gen timestamp
		/
		/	Parms:
		/		Array containing:
		/			'authcode' >> User's (hashed) authcode
		/			'user_id' >> User's ID
		/		
		/	Returns:
		/		Array containing:
		/			'Rows_Affected' >> Number of user rows affected by UPDATE
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/
			  
		$return_arr = array();
		
		try
		{
			$return_arr['Rows_Affected'] = 
				DB::update($this->table_name)
					->set(array(
						'authcode' => $data['authcode']
						,'authcode_gen_dttm' => date('Y-m-d H:i:s')
					))
					->where('user_id', '=', $data['user_id'])
					->execute()
			;
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'AppUser->update_user_authcode()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	
	public function update_user_last_signin_dttm($data)
	{
			  
		/*
		/	Purpose: Set specific user's last login timestamp and invalidate authcode (by
		/					setting it to be 2 hours old)
		/
		/	Parms:
		/		Array containing:
		/			'user_id' >> User's ID
		/		
		/	Returns:
		/		Array containing:
		/			'Rows_Affected' >> Number of user rows affected by UPDATE
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/
			  
		$return_arr = array();
		
		try
		{
			$return_arr['Rows_Affected'] = 
				DB::update($this->table_name)
					->set(array(
						'last_signin_dttm' => date('Y-m-d H:i:s')
						,'authcode_gen_dttm' => date('Y-m-d H:i:s', time() - 7200)
					))
					->where('user_id', '=', $data['user_id'])
					->execute()
			;
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'AppUser->update_user_last_signin_dttm()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	
	public function update_user_password($data)
	{
   		  
		/*
		/	Purpose: Set specific user's password, set account as validated, stamp validation
		/		timestamp, and invalidate authcode (by setting it to be 2 hours old)
		/
		/	Parms:
		/		Array containing:
		/			'password' >> User's (hashed) password
		/			'user_id' >> User's ID
		/		
		/	Returns:
		/		Array containing:
		/			'Rows_Affected' >> Number of user rows affected by UPDATE
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/
   		  
		$return_arr = array();
		
		try
		{
			$return_arr['Rows_Affected'] = 
				DB::update($this->table_name)
					->set(array(
						'user_pswd' => $data['password']
						,'validated' => 1
						,'validated_dttm' => date('Y-m-d H:i:s')
						,'authcode_gen_dttm' => date('Y-m-d H:i:s', time() - 7200)
					))
					->where('user_id', '=', $data['user_id'])
					->execute()
			;
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'AppUser->update_user_password()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	
	

	// Business logic functions

	public function create_user($post)
	{
			  
		/*
		/	Purpose: Create new user
		/
		/	Parms:
		/		'post' >> Submitted form data from Create Acct view
		/
		/	Returns:
		/		Array containing:
		/			'Success' >> Boolean indicating success/failure of user creation
		/		[AND]
		/			'Errors' >> Array containing form validation errors
		*/

		$return_arr = array(
			'Success' => FALSE		  
		);
		
		// Validate Create Acct form entry
		$validation_results_arr = Validation_AppUser::create_acct($post);
		if ($validation_results_arr['Success'])
		{
				  			
			// Validation clean, create new user
			
			$bonafide = Bonafide::instance();
			
			// Generate 32-digit random, alphanumeric authcode      
			$authcode = Text::random('alnum', 32);

			// Generate 32-digit random, alphanumeric initial (dummy) password         
			$user_pswd = Text::random('alnum', 32);
			
			$app_user_data = array(
				'email_addr' => $validation_results_arr['Clean_Post_Data']['emlEmailAddr']
							
				// Username must not start with or end with blanks
				,'username' => trim($validation_results_arr['Clean_Post_Data']['txtUsername'])
				,'user_pswd' => $bonafide->hash($user_pswd)
				,'authcode' => $bonafide->hash($authcode)
			);

			// Perform user creation
			if ($this->create($app_user_data))
			{
					  
				// Send email to user with account creation validation URL
				$tran_email_data = array(
					'recipient' => $app_user_data['email_addr']
					,'sender' => 'acct_validation.sender'
					,'sender_descr' => 'acct_validation.sender_descr'
					,'subject' => 'acct_validation.subject'
					,'body' => 'acct_validation.body'
					,'placeholder_arr' => array(
						'USERNAME' => $app_user_data['username']
						,'EMAIL_ADDR' => $app_user_data['email_addr']
						,'AUTHCODE' => $authcode
					)
				);
				TransactionEmail::send_email($tran_email_data);
				
				// All is well!
				$return_arr['Success'] = TRUE;
			}
		}
		else
		{
			$return_arr['Errors'] = $validation_results_arr['Errors'];
		}
		
		return $return_arr;
		
	}

	public function reset_user_password($post)
	{

		/*
		/	Purpose: Reset user's password
		/
		/	Parms:
		/		'post' >> Submitted form data from Forgot Password view
		/
		/	Returns:
		/		Array containing:
		/			'Clean_Post_Data' >> Array containing cleaned form data
		/			'Success' >> Boolean indicating success/failure of password reset request
		/		[AND]
		/			'Errors' >> Array containing form validation errors
		*/
			  
		$return_arr = array(
			'Success' => FALSE		  
		);

		// Validate Forgot Password form entry
		$validation_results_arr = Validation_AppUser::forgot_password($post);
		if ($validation_results_arr['Success'])
		{
				  			
			// Validation clean, reset user's password
			
			$app_user_data = array(
				'username_or_email_addr' => $validation_results_arr['Clean_Post_Data']['txtUsernameOrEmailAddr']		  					  
			);
			$app_user_rowset = $this->read_user_data_flexible($app_user_data);
			if ($app_user_rowset['Rows_Affected'] == 1)
			{
					  
				// This is a valid username  
				
				// Isolate row of data
				$app_user_arr = $app_user_rowset['Rows'][0];
				
				// Generate 32-digit random, alphanumeric authcode         
				$authcode = Text::random('alnum', 32);
				
				// Update DB with new authcode, new authcode_gen_dttm
				$app_user_data['user_id'] = $app_user_arr['user_id'];
				$app_user_data['authcode'] = Bonafide::instance()->hash($authcode);
				
				$authcode_updated_arr = $this->update_user_authcode($app_user_data);
				if ($authcode_updated_arr['Rows_Affected'] == 1)
				{

					// Send email to user with account reset validation URL
					$tran_email_data = array(
						'recipient' => $app_user_arr['email_addr']
						,'sender' => 'pswd_reset.sender'
						,'sender_descr' => 'pswd_reset.sender_descr'
						,'subject' => 'pswd_reset.subject'
						,'body' => 'pswd_reset.body'
						,'placeholder_arr' => array(
							'USERNAME' => $app_user_arr['username']
							,'EMAIL_ADDR' => $app_user_arr['email_addr']
							,'AUTHCODE' => $authcode
						)
					);
					TransactionEmail::send_email($tran_email_data);
					
					// All is well!
					$return_arr['Success'] = TRUE;
				}
				else
				{
						 
					// Failed user update - pretend all is well to discourage hackers
					$return_arr['Success'] = TRUE;
				}
			}
			else
			{
					  
				// User reset attempt for nonexistent user - pretend all is well to discourage hackers
				$return_arr['Success'] = TRUE;
			}
		}
		else
		{
			$return_arr['Errors'] = $validation_results_arr['Errors'];
		}
		
		return $return_arr;
		
	}

	public function set_password($post)
	{
			  
		/*
		/	Purpose: Set user's password
		/
		/	Parms:
		/		'post' >> Submitted form data from Set Password view
		/
		/	Returns:
		/		Array containing:
		/			'Clean_Post_Data' >> Array containing cleaned form data
		/			'Success' >> Boolean indicating success/failure of password update
		/		[AND]
		/			'Errors' >> Array containing form validation errors
		*/

		$return_arr = array(
			'Success' => FALSE		  
		);
		
		// Validate Create Acct form entry
		$validation_results_arr = Validation_AppUser::set_password($post);
		if ($validation_results_arr['Success'])
		{
				  			
			// Validation clean, update password	

			$app_user_data = array(
				'username' => Session::instance()->get('username')
			);
			
			// Read user's data row
			$app_user_rowset = $this->read_user_data($app_user_data);
			if ($app_user_rowset['Rows_Affected'] == 1)
			{
				
				// Isolate row of data
				$app_user_arr = $app_user_rowset['Rows'][0];
					  
				// Validation objects are read-only (can't trim())
				$user_pswd = $validation_results_arr['Clean_Post_Data']['pwdNewPassword'];
			
				if (trim($user_pswd) == '')
				{
						
					// User has chosen to not pick a password, so this is effectively a single use password
			
					// Generate 32-digit random, alphanumeric authcode to serve as (unknown) password        
					$user_pswd = Text::random('alnum', 32);
				}
				
				$app_user_data['user_id'] = $app_user_arr['user_id'];
				$app_user_data['password'] = Bonafide::instance()->hash($user_pswd);
				
				$password_updated_arr = $this->update_user_password($app_user_data);
				if ($password_updated_arr['Rows_Affected'] == 1)
				{

					// Sign the user in
			
					// Set session vars
					$session = Session::instance();
			
					// NOTE: $session['username'] already set at this point			
					$session->set('user_id', $app_user_arr['user_id']);	
					$session->set('todays_date', date('Y-m-d'));
					$session->set('sel_date', date('Y-m-d'));
			
					// Update last login AND invalidate password reset request (if exists)
					$app_user_data['user_id'] = $session->get('user_id');
					$last_signin_updated = $this->update_user_last_signin_dttm($app_user_data);			
											  
					// All is well!
					$return_arr['Success'] = TRUE;						  
				}
				else
				{
				
					// Password not updated
							
					// Generate system email with appropriate data to track down/recreate error
					$error_data = array(
						'problem_descr' => 'AppUser->set_password()'
							.'<br />User row not updated by call to: this->update_user_password() with user_id = '
							.$app_user_data['user_id']
					);							
					AdminErrorNotif::fatal($error_data);
				}
			}
			else
			{
			
				// User not found
				
				// Generate system email with appropriate data to track down/recreate error
				$error_data = array(
					'problem_descr' => 'AppUser->set_password()'
						.'<br />User row not found by call to: this->read_user_data(); with user_id = '
						.$app_user_data['username']
				);							
				AdminErrorNotif::fatal($error_data);				
			}
		}
		else
		{
			$return_arr['Errors'] = $validation_results_arr['Errors'];
		}
		
		return $return_arr;
		
	}

	public function sign_user_in($post)
	{

		/*
		/	Purpose: Attempt to sign in user
		/
		/	Parms:
		/		'post' >> Submitted form data from Sign In view
		/
		/	Returns:
		/		Array containing:
		/			'Clean_Post_Data' >> Array containing cleaned form data
		/			'Success' >> Boolean indicating success/failure of user sign in
		/		[AND]
		/			'Errors' >> Array containing form validation errors
		*/
			  
		$return_arr = array(
			'Success' => FALSE		  
		);
			  
		// Validate Forgot Password form entry
		$validation_results_arr = Validation_AppUser::sign_in($post);
		if ($validation_results_arr['Success'])
		{
				  			
			// Validation clean, sign in user
			$app_user_data = array(
				'username_or_email_addr' => $validation_results_arr['Clean_Post_Data']['txtUsernameOrEmailAddr']  
			);
			
			// Read user's data row
			$app_user_rowset = $this->read_user_data_flexible($app_user_data);
			if ($app_user_rowset['Rows_Affected'] == 1)
			{
				
				// Isolate row of data
				$app_user_arr = $app_user_rowset['Rows'][0];

				// Set session vars
				$session = Session::instance();			
				$session->set('username', $app_user_arr['username']);			
				$session->set('user_id', $app_user_arr['user_id']);	
				$session->set('todays_date', date('Y-m-d'));
				$session->set('sel_date', date('Y-m-d'));
			
				// Update last login AND invalidate password reset request (if exists)
				$app_user_data['user_id'] = $session->get('user_id');
				$last_signin_updated = $this->update_user_last_signin_dttm($app_user_data);
				
				// Physically delete user's collections and items pending delete
				$collection_model = Model::factory('Collection');
				$delete_data = array(
					'user_id' => $session->get('user_id')
					,'to_delete' => 1
				);
				$users_collections_to_delete_arr = $collection_model->read_one_user($delete_data);				

				// Iterate through user's collections that are flagged for deletion and physically delete them
				foreach ($users_collections_to_delete_arr['Rows'] as $collection_to_delete)
				{					
					$delete_data['collection_id'] = $collection_to_delete['collection_id'];
					$collection_and_item_delete = $collection_model->delete_collection_and_items($delete_data);
				}
     			
				// All is well!
				$return_arr['Success'] = TRUE;
			}
		}
		else
		{
			$return_arr['Errors'] = $validation_results_arr['Errors'];
		}
		
		return $return_arr;
		
	}
	
	public function validate_authcode($email_addr, $authcode)
	{
			  
		/*
		/	Purpose: Validate authcode for user
		/
		/	Parms:
		/		'email_addr' >> User's email address
		/		'authcode' >> User's authcode
		/
		/	Returns:
		/		Array containing:
		/			'Success' >> Boolean indicating success/failure of authcode validation
		*/

		$return_arr = array(
			'Success' => FALSE		  
		);
		
		// Validate URL params passed to this function (from $_GET)
		$get_data_arr = array(
			'email_addr' => trim($email_addr)
			,'authcode' => trim($authcode)
		);
		if (Validation_AppUser::validate_authcode($get_data_arr))
		{
				
			// Validated!
				
			// Read user's username
			$username_arr = $this->read_username_w_email_addr($get_data_arr);
			$username = $username_arr['Column'];
			Session::instance()->set('username', $username);
				
			// All is well!
			$return_arr['Success'] = TRUE;	  
		}
		
		return $return_arr;
		
	}
	

	
	// Static functions
   
	public static function non_blank($fieldname)
	{
			  
		/*
		/	Purpose: Determine if specific field name is non-blank
		/
		/	Parms:
		/		'fieldname' >> Field name to check
		/		
		/	Returns:
		/		Boolean indicating whether field name is non-blank
		*/
			  
		if (trim($fieldname) !== '')
		{
			
			return TRUE;
		}
		
		// IS completely blank!
		return FALSE;
		
	}
	
	public static function read_hashed_authcode_by_email_addr($data)
	{

		/*
		/	Purpose: Read hashed authcode (generated no more than an hour ago),
		/					based on email address
		/
		/	Parms:
		/		Array containing:
		/			'email_addr' >> User's email address
		/
		/	Returns:
		/		Array containing:
		/			'Column' >> Specified user's (hashed) authcode
		/		AND
		/			'Rows_Affected' >> Number of user rows matching specified criteria
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try
		{
			$return_arr['Column'] = 
				DB::select('authcode')
					->from('app_user')
					->where('email_addr', '=', $data['email_addr'])
					->and_where('authcode_gen_dttm', '>=', date('Y-m-d H:i:s', time() - 3600))
					->execute()
					->get('authcode')
			;
			$return_arr['Rows_Affected'] = count($return_arr['Column']);
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'AppUser->read_hashed_authcode_by_email_addr()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	
	public static function read_hashed_pswd_by_username_or_email_addr($data)
	{
		
		/*
		/	Purpose: Read hashed password, based on username OR email address, for 
		/					validated user
		/
		/	Parms:
		/		Array containing:
		/			'username_or_email_addr' >> User's username or email address
		/		
		/	Returns:
		/		Array containing:
		/			'Column' >> Specified user's (hashed) user_pswd
		/		AND
		/			'Rows_Affected' >> Number of user rows matching specified criteria
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/
			  
		$return_arr = array();
		
		try
		{
			$return_arr['Column'] = 
				DB::select('user_pswd')
					->from('app_user')
					->where('validated', '=', 1)
					->and_where_open()
						->where('email_addr', '=', $data['username_or_email_addr'])
						->or_where('username', '=', $data['username_or_email_addr'])
					->and_where_close()
					->execute()
					->get('user_pswd')
			;
			$return_arr['Rows_Affected'] = count($return_arr['Column']);		
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'AppUser->read_hashed_pswd_by_username_or_email_addr()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	
	public static function unique_emailaddr($email_addr)
	{
		
		/*
		/	Purpose: Determine if specific email address name is unique (unused)
		/
		/	Parms:
		/		$email_addr >> User's email address
		/		
		/	Returns:
		/		Boolean indicating whether email address is unique
		*/
			  
		return Model_AppUser::unique_string($email_addr, 'email_addr');
		
	}
   
	public static function unique_username($username)
	{
			  
		/*
		/	Purpose: Determine if specific username is unique (unused)
		/
		/	Parms:
		/		$username >> User's username
		/		
		/	Returns:
		/		Boolean indicating whether username is unique
		*/
			  
		return Model_AppUser::unique_string($username, 'username');
		
	}

	public static function unique_string($string_to_validate, $column_name)
	{
			
		/*
		/	Purpose: Determine if specific value is unique (unused) in specific column
		/
		/	Parms:
		/		$string_to_validate >> String to check for uniqueness
		/		$column_name >> app_user table column to search in
		/		
		/	Returns:
		/		Boolean indicating whether specific value is unique
		/		OR
		/		Generates fatal error, sending error notif to admin
		*/
	
		try
		{
			return ! 
				DB::select(array(DB::expr('COUNT('.$column_name.')'), 'total_count'))
					->from('app_user')
					->where($column_name, '=', $string_to_validate)
					->execute()
					->get('total_count')
			;
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'AppUser->unique_string()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
	}
	
	public static function valid_curr_authcode($email_addr, $authcode)
	{
		
		/*
		/	Purpose: Determine if specified authcode is valid
		/
		/	Parms:
		/		$email_addr >> User's email address
		/		$authcode >> User's specified (plaintext) authcode
		/		
		/	Returns:
		/		Boolean indicating success/failure of comparison of specified (hashed) 
		/			authcode to specific user's (hashed) authcode in database
		*/
   		  
		$app_user_data = array(
			'email_addr' => $email_addr
		);
   		 
		// Return TRUE if user's entered authcode matches what is set in DB as their current authcode
		$hashed_authcode_arr = Model_AppUser::read_hashed_authcode_by_email_addr($app_user_data);
		if ($hashed_authcode_arr['Rows_Affected'] == 0)
		{
   			  
			// No user row found!
			return FALSE;
		}
   	
		$hashed_authcode = $hashed_authcode_arr['Column'];			  

		if (($hashed_authcode) AND (Bonafide::instance()->check($authcode, $hashed_authcode)))
		{
   		
			// User-supplied authcode matches what's stored in DB
			return TRUE;
		}
		else
		{

			return FALSE;	  
		}
		
	}
	
	public static function valid_curr_pswd($username_email_addr, $password)
	{
   		
		/*
		/	Purpose: Determine if specified password is valid
		/
		/	Parms:
		/		$username_email_addr >> User's username or email address
		/		$password >> User's entered (plaintext) password
		/		
		/	Returns:
		/		Boolean indicating success/failure of comparison of specified (hashed) 
		/			password to specific user's (hashed) password in DB
		*/
   		  
		$app_user_data = array(
			'username_or_email_addr' => $username_email_addr
			,'password' => $password
		);
   		 
		// Return TRUE if user's entered password matches what is set in DB as their current password
		$hashed_pswd_arr = Model_AppUser::read_hashed_pswd_by_username_or_email_addr($app_user_data);
		if ($hashed_pswd_arr['Rows_Affected'] == 0)
		{
   			  
			// No user row found!
			return FALSE;
		}
   	
		$hashed_pswd = $hashed_pswd_arr['Column'];			  

		if (($hashed_pswd) AND (Bonafide::instance()->check($app_user_data['password'], $hashed_pswd)))
		{
   		
			// User-entered password matches what's stored in DB
			return TRUE;
		}
		else
		{
			
			return FALSE;	  
		}
		
	}
   
} // End Model_AppUser
