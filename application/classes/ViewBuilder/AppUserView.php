<?php defined('SYSPATH') or die('No direct script access.');

class ViewBuilder_AppUserView {
	
	public function create_acct($post_or_null, $errors)
	{
			  
		/*
		/	Purpose: Build Create Acct view
		/
		/	Parms:
		/		'post_or_null' >> Submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		View to render
		*/			
		
		$content = 

			// Render Create Acct view		
			View::factory('Create_Acct')
				->set('page_description', 'Create Account')
				->set('post', $post_or_null)
				->set('errors', $errors)
		;
      
		return $content;
		
	}
	
	public function forgot_pswd($post_or_null, $errors)
	{
			  
		/*
		/	Purpose: Build Forgot Password view
		/
		/	Parms:
		/		'post_or_null' >> Submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		View to render
		*/			
		
		$content = 
		
			// Render Forgot Pswd view		
			View::factory('Forgot_Pswd')
				->set('page_description', 'Reset Password')
				->set('post', $post_or_null)
				->set('errors', $errors)
		;
      
		return $content;
		
	}
	
	public function informational_message($page_descr, $message)
	{
			  
		/*
		/	Purpose: Build Informational Message view
		/
		/	Parms:
		/		'page_descr' >> Page description
		/		'message' >> Message to display on page
		/
		/	Returns:
		/		View to render
		*/			

		$content = 
		
			// Render Informational Message view		
			View::factory('Informational_Message')
				->set('info_message', $message)
				->set('page_description', $page_descr)
		;
      
		return $content;
		
	}	
	
	public function set_password($submit_handler, $post_or_null, $errors)
	{
			  
		/*
		/	Purpose: Build Set Password view
		/
		/	Parms:
		/		'submit_handler' >> Action to submit form to
		/		'post_or_null' >> Submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		View to render
		*/			
		
		$content = 
		
			// Render Set Pswd view		
			View::factory('Set_Pswd')
				->set('submit_handler', $submit_handler)
				->set('page_description', 'Set Password')
				->set('post', $post_or_null)
				->set('errors', $errors)
		;
      
		return $content;
		
	}
	
	public function sign_in($post_or_null, $errors)
	{
			  
		/*
		/	Purpose: Build Sign In view
		/
		/	Parms:
		/		'post_or_null' >> Submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		View to render
		*/			
		
		$content = 
		
			// Include message block
			View::factory('Message_Block')
			
			// Render Sign In view		
			.View::factory('Sign_In')
				->set('page_description', 'Sign In')
				->set('post', $post_or_null)
				->set('errors', $errors)
		;
      
		return $content;
		
	}
	
} // End ViewBuilder_AppUserView
