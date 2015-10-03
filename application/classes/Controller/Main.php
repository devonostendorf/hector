<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Main extends Controller_Template_Sitepage {

	public function before()
	{
		
		/*
		/	Purpose: Ensure user is signed in before actions in controller are called
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		parent::before();  	  
		if ( ! (Session::instance()->get('user_id')))
		{
					
			// User is NOT signed in, redirect to Sign In page
			$this->redirect('User');			
		}
		
	}
	
	public function action_index()
	{

		/*
		/	Purpose: Default action - redirects to Collection controller
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		// Redirect to Collection's default action, by default
		$this->redirect('Collection');	
		
	}
	
} // End Main	
