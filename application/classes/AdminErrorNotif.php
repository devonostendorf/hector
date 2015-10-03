<?php defined('SYSPATH') or die('No direct script access.');

class AdminErrorNotif {

	public static function fatal($data)
	{				
			
		/*
		/	Purpose: Send DB Exception info to admin
		/
		/	Parms:
		/		Array containing:
		/			'problem_descr' >> Function name and exception text
		/		
		/	Returns:
		/		[NONE]
		*/
		
		// Retrieve subject and body of validation email from email_text messages file
		$email_subject = Kohana::message('email_text', 'system_error_report.subject');
		$email_body = Kohana::message('email_text', 'system_error_report.body');
      	
		// Make substitutions
		$email_body = str_replace('[USER_ID]', Session::instance()->get('user_id'), $email_body);
		$email_body = str_replace('[DTTM]', date('Y-m-d H:i:s'), $email_body);
		$email_body = str_replace('[CONTROLLER]', Request::current()->controller(), $email_body);
		$email_body = str_replace('[ACTION]', Request::current()->action(), $email_body);
		$email_body = str_replace('[PROBLEM_DESCR]', $data['problem_descr'], $email_body);

		// Create email object, default message to '', as we want to override it to use HTML formatting      	
		$email = Email::factory($email_subject, '')
			->to(Kohana::message('email_text', 'system_error_report.recipient'))
			->from(Kohana::message('email_text', 'system_error_report.sender'),
				Kohana::message('email_text', 'system_error_report.sender_descr'));
      	
		// Identify that email body contains HTML
		$email->message($email_body, 'text/html');
       		
		// Actually send email
		$email->send();
				     	
		// All DB exceptions are fatal, redirect to sign in page and display error message
		$session = Session::instance();
		$session->delete('message');
		$session->set('error', 'SYSTEM ERROR ENCOUNTERED - system support has been notified - please try again later.');

		// Redirect to User/sign_in action
		HTTP::redirect('User/sign_in');
		
	}
	
} // End AdminErrorNotif
