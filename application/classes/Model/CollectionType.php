<?php defined('SYSPATH') or die('No direct script access.');

class Model_CollectionType extends Model_Database {
		  
	public function __construct()  
	{

		/*
		/	Purpose: Create collection type model
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		parent::__construct();
		$this->table_name = 'collection_type';  
		
		// NOTE: Deliberately omit coll_type_id so it is auto-assigned
		$this->table_cols = array(
			'descr'
		);
		
	}
	
	
	
	// SQL functions

	public function read()
	{			  
			  
		/*
		/	Purpose: Read all collection types
		/
		/	Parms:
		/		[NONE]
		/
		/	Returns:
		/		Array containing:
		/			'Rows' >> Array of all collection types in system
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try
		{
			$return_arr['Rows'] = 
				DB::select()
					->from($this->table_name)
					->order_by('descr')
					->execute()
			;
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error,
			//		redirect user to sign in page, and display error message
			$error_data = array(
				'problem_descr' => 'CollectionType->read()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	

	
	// Business logic functions
	
	public function read_all_collection_types()
	{
			  
		/*
		/	Purpose: Read all collection types and store in array, indexed by ID
		/
		/	Parms:
		/		[NONE]
		/
		/	Returns:
		/		Array containing collection types, indexed by coll_type_id
		*/
			
		$return_arr = array();

		// Read all collection types
		$collection_type_arr = $this->read();
		
		// Populate select array for collection types
		foreach ($collection_type_arr['Rows'] as $type_index => $type)
		{
			$return_arr[$type['coll_type_id']] = $type['descr'];
		}
		
		return $return_arr;
		
	}



	// Static functions	
	
	
	
} // End Model_CollectionType
