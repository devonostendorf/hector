<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Validate extends Controller_Template_Sitepage {

	public function action_index()
	{

		/*
		/	Purpose: Default action - redirects to invalid URL action
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		// Redirect to invalid_url action
		$this->redirect('Validate/invalid_url');
		
	}

	public function action_invalid_url()		
	{
		
		/*
		/	Purpose: Action to handle invalid URLs
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		// Render Invalid URL message page		
		$this->template->content = ViewBuilder::factory('Page')->informational_message($this->template, 'Invalid URL', Kohana::message('misc_text', 'bad_validation_url'));
		
	}

	public function action_new_acct()
	{		
		
		/*
		/	Purpose: Action to handle "new account validation" requests via
		/					call to set_password function
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		$this->set_password('Validate/new_acct_prcs');
		
	}
	
	public function action_new_acct_prcs()
	{		
		
		/*
		/	Purpose: Action to handle processing of "new account validation" requests via
		/					call to set_password_prcs function
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		$this->set_password_prcs('Validate/new_acct_prcs');
		
	}
	
	public function action_reset_acct()
	{		
		
		/*
		/	Purpose: Action to handle "reset password validation" requests via
		/					call to set_password function
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		$this->set_password('Validate/reset_acct_prcs');
		
	}

	public function action_reset_acct_prcs()
	{		
		
		/*
		/	Purpose: Action to handle processing of "reset password validation" requests via
		/					call to set_password_prcs function
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		$this->set_password_prcs('Validate/reset_acct_prcs');
		
	}
	
	
	
	// Private functions to do (generic) heavy lifting

	private function set_password($submit_handler)
	{
			  
		/*
		/	Purpose: Validate authcode for new account or password reset request
		/					and then render "set password" page
		/
		/	Parms:
		/		'submit_handler' >> Controller/action to set as action for form on rendered page 
		/
		/	Returns:
		/		[NONE]
		*/

		// Grab email addr from URL
		$email_addr = $this->request->param('email_addr');
		
		// Grab authcode from URL
		$authcode = $this->request->param('authcode');
	
		$authcode_valid_arr = Model::factory('AppUser')->validate_authcode($email_addr, $authcode);
		if ($authcode_valid_arr['Success'])
		{

			// Render Set Password page
			$this->template->content = ViewBuilder::factory('Page')->set_password($submit_handler, $this->template, NULL, $errors = array());				  
		}
		else
		{
				  
			// Failed validation
				
			// Redirect to invalid_url action
			$this->redirect('Validate/invalid_url');				  
		}
		
	}
	
	private function set_password_prcs($submit_handler)
	{
			  
		/*
		/	Purpose: Process submitted form from "set password" page
		/
		/	Parms:
		/		'submit_handler' >> Controller/action to set as action for form on rendered page 
		/
		/	Returns:
		/		[NONE]
		*/

		$session = Session::instance();
			  
		// Grab $_POST array from submitted form
		$post = $this->request->post();
		
		if (isset($post['btnUpdate']))
		{

			// Update button was pressed
			$password_set_arr = Model::factory('AppUser')->set_password($post);
			if ($password_set_arr['Success'])
			{
					  
				// Set successful sign in message,
				$session->set('message', 'Welcome back, '.$session->get('username'));
			
				// Redirect to main page
				$this->redirect('Main');				  
			}	  
			else
			{
		
				// Validation failed, render Set Password page with custom error text displayed on appropriate form field(s)
				$this->template->content = ViewBuilder::factory('Page')->set_password($submit_handler, $this->template, $post, $password_set_arr['Errors']);				  
			}
		}
		else	// This "else" is required so that failed validation (above) won't auto redirect!
		{
		
			// Redirect to invalid_url action
			$this->redirect('Validate/invalid_url');				  
		}
		
	}
	
} // End Validate
