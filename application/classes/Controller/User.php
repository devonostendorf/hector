<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Template_Sitepage {

	public function action_index()
	{

		/*
		/	Purpose: Default action - routes to sign in action
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		// Route to sign_in action, by default
		$this->action_sign_in();
		
	}
	
	public function action_create_acct()
	{		
		
		/*
		/	Purpose: Action to handle "create account" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		// Render Create Account page
		$this->template->content = ViewBuilder::factory('Page')->create_acct($this->template, NULL, $errors = array());
		
	}
	
	public function action_create_acct_prcs()
	{		
		
		/*
		/	Purpose: Action to handle processing of "create account" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		// Grab $_POST array from submitted form
		$post = $this->request->post();
		
		if (isset($post['btnCreateAcct']))
		{
				  
			// Create button was pressed
		
			$account_created_arr = Model::factory('AppUser')->create_user($post);
			if ($account_created_arr['Success'])
			{
				  
				// Successful account creation
		
				// Redirect to create_acct_done action for cleaner resultant URL
				$this->redirect('User/create_acct_done');
			}
			else
			{
		
				// Validation failed, render Create Account page with custom error text displayed on appropriate form field(s)
				$this->template->content = ViewBuilder::factory('Page')->create_acct($this->template, $post, $account_created_arr['Errors']);
			}
		}
		else	// This "else" is required so that failed validation (above) won't auto redirect!
		{
		
			// Bad URL, redirect to create_acct action
			$this->redirect('User/create_acct');
		}		
		
	}	

	public function action_create_acct_done()
	{		
		
		/*
		/	Purpose: Action to handle post-processing of "create account" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		// Render Informational Message page instructing user to check email for URL to click to complete acct creation process
		$this->template->content = ViewBuilder::factory('Page')->informational_message($this->template, 'Account Confirmation Required', Kohana::message('misc_text', 'acct_creation_requested'));
		
	}
	
	public function action_forgot_pswd()
	{		
		
		/*
		/	Purpose: Action to handle "forgot password" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		// Define arrays
		$errors = array();
				
		// Render Forgot Password page
		$this->template->content = ViewBuilder::factory('Page')->forgot_pswd($this->template, NULL, $errors = array());
		
	}	
	
	public function action_forgot_pswd_prcs()
	{		
		
		/*
		/	Purpose: Action to handle processing of "forgot password" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		// Grab $_POST array from submitted form
		$post = $this->request->post();
  		
		if (isset($post['btnResetPswd']))
		{

			// Reset Password button was pressed
			
			$password_reset_arr = Model::factory('AppUser')->reset_user_password($post);
			if ($password_reset_arr['Success'])
			{

				// Successful password reset
		
				// Redirect to forgot pswd done action for cleaner resultant URL
				$this->redirect('User/forgot_pswd_done');					  
			}
			else
			{
			
				// Validation failed, render Forgot Password page with custom error text displayed on appropriate form field(s)
				$this->template->content = ViewBuilder::factory('Page')->forgot_pswd($this->template, $post, $password_reset_arr['Errors']);					  
			}				  
		}
		else	// This "else" is required so that failed validation (above) won't auto redirect!
		{
		
			// Bad URL, redirect to create_acct action
			$this->redirect('User/create_acct');
		}
		
	}
	
	public function action_forgot_pswd_done()
	{		
		
		/*
		/	Purpose: Action to handle post-processing of "forgot password" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		// Render Informational Message page instructing user to check email for URL to click to complete acct reset process
		$this->template->content = ViewBuilder::factory('Page')->informational_message($this->template, 'Account Confirmation Required', Kohana::message('misc_text', 'pswd_reset_requested'));
		
	}
 	
	public function action_sign_in()
	{		
		
		/*
		/	Purpose: Action to handle "sign in" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		// Render Sign In page
		$this->template->content = ViewBuilder::factory('Page')->sign_in($this->template, NULL, $errors = array());
		
	}	
	
	public function action_sign_in_prcs()
	{		
		
		/*
		/	Purpose: Action to handle processing of "sign in" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		$session = Session::instance();

		// Grab $_POST array from submitted form
		$post = $this->request->post();
		
		if (isset($post['btnSignIn']))
		{
				  
			// Sign In button was pressed
			$user_sign_in_arr = Model::factory('AppUser')->sign_user_in($post);
			if ($user_sign_in_arr['Success'])
			{
					  
				// Successful user sign in
				
				// Set successful sign in message
				$session->set('message', 'Welcome back, '.$session->get('username'));
			
				// Redirect to main page
				$this->redirect('Main');					  
			}
			else
			{
		
				// Validation failed, render Sign In page with custom error text displayed on appropriate form field(s)
				$this->template->content = ViewBuilder::factory('Page')->sign_in($this->template, $post, $user_sign_in_arr['Errors']);
			}
		}
		else	// This "else" is required so that failed validation (above) won't auto redirect!
		{
		
			// Bad URL, redirect to sign_in action
			$this->redirect('User/sign_in');
		}		
		
	}		
	
	public function action_sign_out()
	{		
		
		/*
		/	Purpose: Action to handle "sign out" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		// Unset all Session vars
		$session = Session::instance();
		$session_vars = $session->as_array();
		foreach ($session_vars as $session_var_key => $session_var)
		{
			$session->delete($session_var_key); 
		}
		
		// Redirect to main page
		$this->redirect('User/sign_in');	
		
	}
	
} // End User
