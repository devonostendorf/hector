<?php defined('SYSPATH') or die('No direct script access.');

return array
(
	'acct_validation' => array(
		'sender' => '[system_email_address]'
		,'sender_descr' => 'Hector'
		,'subject' => 'Hector - Please complete your account creation request within 60 minutes.'
		,'body' => "Hi [USERNAME], <br /><br />Thank you for signing up for a Hector account! <br /><br />In order to complete your account activation, please click on the following link within the next 60 minutes (after which this URL will expire).&nbsp;&nbsp;Doing so will prompt you to choose a password for your account: <br /><br />https://[site]/Validate/new_acct/[EMAIL_ADDR]/[AUTHCODE] <br /><br />Thanks, <br />&nbsp;&nbsp;&nbsp;The Organizer"
	) 
	,'pswd_reset' => array(
		'sender' => '[system_email_address]'
		,'sender_descr' => 'Hector'
		,'subject' => 'Hector - Please complete your password reset request within 60 minutes.'
		,'body' => "Hi [USERNAME], <br /><br />Sorry to hear you're unable to sign in to Hector! <br /><br />In order to confirm that you control this email address, please click on the following link within the next 60 minutes (after which this URL will expire).&nbsp;&nbsp;Doing so will prompt you to choose a new password for your account: <br /><br />http://[site]/Validate/reset_acct/[EMAIL_ADDR]/[AUTHCODE] <br /><br />If you did NOT request a password reset, please ignore this email and continue to sign in with your current password. <br /><br />Thanks, <br />&nbsp;&nbsp;&nbsp;The Organizer"
	) 
	,'system_error_report' => array(
		'sender' => '[system_email_address]'
		,'recipient' => '[admin_email_address]'
		,'sender_descr' => 'Hector'
		,'subject' => 'Hector - ALERT - system problem!'
		,'body' => "Hey there, <br /><br />Sorry to be the bearer of bad news, but the following error occurred:<br /><br />User: [USER_ID]<br />At: [DTTM]<br /><br />Controller: [CONTROLLER]<br />Action: [ACTION]<br /><br />[PROBLEM_DESCR]<br /><br />Please look into this!<br /><br />Thanks,<br />&nbsp;&nbsp;&nbsp;The Innards"
	)
);
