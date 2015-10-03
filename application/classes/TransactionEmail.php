<?php defined('SYSPATH') or die('No direct script access.');

class TransactionEmail {

	public static function send_email($data)
	{				
			  
		/*
		/	Purpose: Send transactional email to user
		/
		/	Parms:
		/		Array containing:
		/			'recipient' >> Email recipient
		/			'sender' >> Email sender
		/			'sender_descr' >> Email sender description
		/			'subject' >> Email subject
		/			'body'	>> Email body
		/			'placeholder_arr' >> Array keyed by email body placeholders and containing
		/				replacement values for placeholders 
		/		
		/	Returns:
		/		NULL
		*/
		
		// Retrieve subject and body of validation email from email_text messages file
		$email_subject = Kohana::message('email_text', $data['subject']);
		$email_body = Kohana::message('email_text', $data['body']);
      	
		// Make substitutions
		foreach ($data['placeholder_arr'] as $placeholder => $placeholder_val)
		{		
			$email_body = str_replace('['.$placeholder.']', $placeholder_val, $email_body);				  
		}
		
		// Create email object, default message to '', as we want to override it to use HTML formatting      	
		$email = Email::factory($email_subject, '')
			->to($data['recipient'])
			->from(Kohana::message('email_text', $data['sender']), 
				Kohana::message('email_text', $data['sender_descr']));
      	
		// Identify that email body contains HTML
		$email->message($email_body, 'text/html');
       		
		// Actually send email
		$email->send();
				     	
		return;
		
	}
	
} // End TransactionEmail
