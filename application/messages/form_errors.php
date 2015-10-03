<?php defined('SYSPATH') or die('No direct script access.');

return array
(
		  
	// Account creation error messages
	'emlEmailAddr' => array(
		'email' => "Oops! That doesn't look like a valid email address."
		,'max_length' => 'Please keep your email address under 100 chars.'
		,'Model_AppUser::unique_emailaddr' => 'Oops! That email address is already being used.'             
	)
	,'pwdConfPassword' => array(
		'matches' => 'Please be sure your new password and confirmed new password match.'
	)
	,'pwdPswd' => array(
		'Model_AppUser::valid_curr_pswd' => 'The Username (or Email Address) and password combo you entered is invalid.'
		,'not_empty' => 'Oops! Looks like you forgot to enter your password.'
	)
	,'txtUsername' => array(
		'max_length' => 'Please keep your Username under 100 chars.'
		,'Model_AppUser::non_blank' => "C'mon now..you can't have a blank Username."             
		,'Model_AppUser::unique_username' => 'Oops! That Username is already being used.'
		,'not_empty' => 'Oops! You forgot to enter your Username.'
	)
	,'txtUsernameOrEmailAddr' => array(
		'Model_AppUser::non_blank' => "C'mon now..you can't have a blank Username or Email Address."              
		,'not_empty' => 'Username or Email Address must be entered.'
	)
  
	// Collection creation error messages           
	,'txtCollectionName' => array (
		'max_length' => 'Collection name must be 75 chars or less.'
		,'not_empty' => "Collection name can't be blank!"
	)
	
	// Item creation error messages           
	,'txtItemName' => array (
		'max_length' => 'Item name must be 75 chars or less.'
		,'not_empty' => "Item name can't be blank!"
	)
	,'txtItemSeq' => array (
		'not_empty' => "Item sequence can't be blank!"
		,'numeric' => 'Item sequence must be between 1 and 999!'
		,'range' => 'Item sequence must be between 1 and 999!'
	)
);
