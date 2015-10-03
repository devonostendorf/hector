<?php defined('SYSPATH') or die('No direct script access.');

class Validation_Item extends Validation {

	public static function form_fields($post)
	{
			
		/*
		/	Purpose: Validate item form submitted data
		/
		/	Parms:
		/		Array containing:
		/			'txtItemSeq' >> New item's sequence number
		/			'txtItemName' >> New item's name
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
					
			// Item sequence number must be non-blank
			->rule('txtItemSeq', 'not_empty')
			
			// Item sequence number must be numeric
			->rule('txtItemSeq', 'numeric')

			// Item sequence number must be a valid number between 1 and 999
			->rule('txtItemSeq', 'range', array(':value', 1, 999))		

			// Item name must be non-blank
			->rule('txtItemName', 'not_empty')
				
			// Item name must not be more than 75 chars long
			->rule('txtItemName', 'max_length', array(':value', 75))
			
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
	
} // End Validation_Item
