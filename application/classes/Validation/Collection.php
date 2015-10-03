<?php defined('SYSPATH') or die('No direct script access.');

class Validation_Collection extends Validation {

	public static function form_fields($post)
	{
			
		/*
		/	Purpose: Validate collection form submitted data
		/
		/	Parms:
		/		Array containing:
		/			'txtCollectionName' >> New collection's name
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
					
			// Collection name must be non-blank
			->rule('txtCollectionName', 'not_empty')
				
			// Collection name must not be more than 75 chars long
			->rule('txtCollectionName', 'max_length', array(':value', 75))
			
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
	
} // End Validation_Collection
