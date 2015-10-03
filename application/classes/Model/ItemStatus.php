<?php defined('SYSPATH') or die('No direct script access.');

class Model_ItemStatus extends Model_Database {
		  
	public function __construct()  
	{

		/*
		/	Purpose: Create item status model
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		parent::__construct();
		$this->table_name = 'item_status';  
		
		$this->table_cols = array(
			'item_status_cd'
			,'descr'
			,'seq'
		);
		
	}
	
	
	
	// SQL functions
	
	public function read()
	{			  
			  
		/*
		/	Purpose: Read all item statuses
		/
		/	Parms:
		/		[NONE]
		/
		/	Returns:
		/		Array containing:
		/			'Rows' >> Array of all item statuses in system
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try
		{
			$return_arr['Rows'] = 
				DB::select()
					->from($this->table_name)
					->order_by('seq')
					->execute()
			;
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error,
			//		redirect user to sign in page, and display error message
			$error_data = array(
				'problem_descr' => 'ItemStatus->read()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}

	
	
	// Business logic functions
	
	public function read_all_item_statuses()
	{
			  
		/*
		/	Purpose: Read all item statuses and store in array, indexed by ID
		/
		/	Parms:
		/		[NONE]
		/
		/	Returns:
		/		Array containing item statuses, indexed by item_status_cd
		*/
			
		$return_arr = array();

		// Read all item statuses
		$item_status_arr = $this->read();
		
		// Populate select array for item statuses
		foreach ($item_status_arr['Rows'] as $status_index => $status)
		{
			$return_arr[$status['item_status_cd']] = $status['descr'];
		}
		
		return $return_arr;
		
	}



	// Static functions	
	
	
	
} // End Model_ItemStatus
