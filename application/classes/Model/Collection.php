<?php defined('SYSPATH') or die('No direct script access.');

class Model_Collection extends Model_Database {
		  
	public function __construct()  
	{
		
		/*
		/	Purpose: Create collection model
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		parent::__construct();
		$this->table_name = 'collection';  
		
		// NOTE: Deliberately omit collection_id so it is auto-assigned
		$this->table_cols = array(
			'user_id'
			,'descr'
			,'coll_type_id'
			,'to_delete'
			,'last_update_dttm'
		);		
		
	}
	
	
	
	// SQL functions

	public function create($data)
	{

		/*
		/	Purpose: Create new collection row in database
		/
		/	Parms:
		/		Array containing:
		/			'user_id' >> User ID of creator of new collection
		/			'descr' >> New collection's description
		/			'coll_type_id' >> New collection's type ID
		/		
		/	Returns:
		/		Array containing:
		/			'Row_Created_ID' >> New collection's collection_id
		/		AND
		/			'Rows_Affected' >> Number of rows (1) inserted into table
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try
		{
			list($return_arr['Row_Created_ID'], $return_arr['Rows_Affected']) = 
				DB::insert($this->table_name)
					->columns($this->table_cols)
					->values(array(
						$data['user_id']
						,$data['descr']
						,$data['coll_type_id']
				
						// Default to_delete to FALSE
						,FALSE
				
						// Default last_update_dttm to current datetime
						,date('Y-m-d H:i:s') 
					))
					->execute()
			;
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'Collection->create()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}

	public function delete_collection_and_items($data)
	{
		
		/*
		/	Purpose: Delete all collections, flagged for deletion, for one (specified) user
		/
		/	Parms:
		/		Array containing:
		/			'user_id' >> User ID to delete flagged collections for
		/			'collection_id' >> ID of collection to delete
		/
		/	Returns:
		/		Array containing:
		/			'Rows_Affected' >> Number of collection rows deleted
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/
		
		$return_arr = array();
   
		// Need to handle physical delete of collection and its associated items as an
		//		atomic operation
		$db = Database::instance('default');
		$db->begin();
 
		try 
		{
			// Delete collection row
			$return_arr['Rows_Affected'] = 
				DB::delete($this->table_name)
					->where('user_id', '=', $data['user_id'])
					->and_where('collection_id', '=', $data['collection_id'])
					->and_where('to_delete', '=', 1)
					->execute()
			;
			
			// Delete item row
			$dummy = 
				DB::delete('item')
					->where('collection_id', '=', $data['collection_id'])
					->and_where('to_delete', '=', 1)
					->execute()
			;		
			
 			// Deletes were successful, commit the changes
 			$db->commit();
			
		}
		catch (Database_Exception $e)
		{
		
			// Delete failed, roll back changes
			$db->rollback();

			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'Collection->delete_collection_and_items()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;		
		
	}

	public function read_exists($data)
	{
		
		/*
		/	Purpose: Confirm/refute existence of specific collection for one (specified) user
		/
		/	Parms:
		/		Array containing:
		/			'user_id' >> User ID to find collection for
		/			'collection_id' >> Collection ID of collection to find
		/
		/	Returns:
		/		Array containing:
		/			'Rows_Affected' >> Number of collection rows (0 or 1) matching specified criteria
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try
		{
			$return_arr['Rows_Affected'] = 
				DB::select(array(DB::expr('COUNT(collection_id)'), 'total_count'))
					->from($this->table_name)
					->where('user_id', '=', $data['user_id'])
					->and_where('collection_id', '=', $data['collection_id'])
					->execute()
					->get('total_count', 0)
			;
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'Collection->read_exists()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	
	public function read_one($data)
	{			  
			  
		/*
		/	Purpose: Read specific collection for one (specified) user
		/
		/	Parms:
		/		Array containing:
		/			'user_id' >> User ID to return collection for
		/			'collection_id' >> Collection ID of collection to return
		/
		/	Returns:
		/		Array containing:
		/			'Rows' >> Array of specified collection columns
		/		AND
		/			'Rows_Affected' >> Number of collection rows in 'Rows'
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try
		{
			$return_arr['Rows'] = 
				DB::select(
					'collection_id'
					,array($this->table_name.'.descr', 'name')
					,array($this->table_name.'.coll_type_id', 'type_id')				  
					,array('collection_type.descr', 'type')
					,array($this->table_name.'.last_update_dttm', 'last_update_dttm')				  
				)
					->from($this->table_name)
					->join('collection_type')
						->on($this->table_name.'.coll_type_id', '=', 'collection_type.coll_type_id')
					->where('user_id', '=', $data['user_id'])
					->and_where('collection_id', '=', $data['collection_id'])
					->execute()
			;		
			$return_arr['Rows_Affected'] = count($return_arr['Rows']);
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'Collection->read_one()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}	

	public function read_one_user($data)
	{			  
			  
		/*
		/	Purpose: Read all collections for one (specified) user
		/
		/	Parms:
		/		Array containing:
		/			'user_id' >> User ID to return collections for
		/			'to_delete' >> Flag indicating whether collection is flagged for deletion
		/
		/	Returns:
		/		Array containing:
		/			'Rows' >> Array containing specified user's collection rows
		/		AND
		/			'Rows_Affected' >> Number of collection rows in 'Rows'
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try 
		{		
			$return_arr['Rows'] = 
				DB::select(
					'collection_id'
					,array($this->table_name.'.descr', 'name')
					,array('collection_type.descr', 'type')
				)
					->from($this->table_name)
					->join('collection_type')
						->on($this->table_name.'.coll_type_id', '=', 'collection_type.coll_type_id')
					->where('user_id', '=', $data['user_id'])
					->and_where('to_delete', '=', $data['to_delete'])
					->execute()
			;	
			$return_arr['Rows_Affected'] = count($return_arr['Rows']);
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error,
			//		redirect user to sign in page, and display error message
			$error_data = array(
				'problem_descr' => 'Collection->read_one_user()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
   
	public function update($data)
	{

		/*
		/	Purpose: Updates specified collection
		/
		/	Parms:
		/		Array containing:
		/			'user_id' >> User ID to update collection for
		/			'collection_id' >> ID of collection to update
		/			'descr' >> Collection's updated description
		/			'coll_type_id' >> Collection's updated type ID
		/
		/	Returns:
		/		Array containing:
		/			'Rows_Affected' >> Number of collection rows affected by UPDATE
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try
		{
			$return_arr['Rows_Affected'] = 
				DB::update($this->table_name)
					->set(array(
						'descr' => $data['descr']
						,'coll_type_id' => $data['coll_type_id']
					))
					->where('user_id', '=', $data['user_id'])
					->and_where('collection_id', '=', $data['collection_id'])
					->execute()
			;
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'Collection->update()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}   
	
	public function update_to_delete($data)
	{
   
		/*
		/	Purpose: Flags (or unflags) specified collection, for deletion
		/
		/	Parms:
		/		Array containing:
		/			'collection_id' >> ID of collection to delete/undelete
		/			'to_delete' >> Flag indicating whether to delete or undelete collection
		/			'last_update_dttm' >> Collection's last update DATETIME
		/
		/	Returns:
		/		Array containing:
		/			'Rows_Affected' >> Number of collection rows affected by UPDATE
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/
 
		$return_arr = array();
   
		// Need to handle delete/undelete of collection and its associated items as an
		//		atomic operation
		$db = Database::instance('default');
		$db->begin();
 
		try 
		{
 			
			// "Delete"/Undelete collection row
			$return_arr['Rows_Affected'] = 
				DB::update($this->table_name)
					->set(array(
						'to_delete' => $data['to_delete']
						,'last_update_dttm' => $data['last_update_dttm'] 
					))
					->where('collection_id', '=', $data['collection_id'])
					->execute()
			;
			
			// "Delete"/Undelete ITEM row(s) (if any) belonging to this collection
			$query = 
				DB::update('item')
					->set(array(
						'to_delete' => $data['to_delete']
						,'last_update_dttm' => $data['last_update_dttm']
					))
					->where('collection_id', '=', $data['collection_id'])
			;
		
			if ($data['to_delete'] == 1)
			{
				  
				// This is setting a delete, so last_update_dttm needs to be set to match what was 
				//		used for the collection to allow for potential Undo
				$query
					->and_where('to_delete', '=', 0)
				;
			}
			else
			{
				  
				// This is unsetting a delete, so the last_update_dttm matching collection row needs
				//		to be in the WHERE clause to ONLY unset those rows deleted with collection (as
				//		opposed to those that might have been flagged for earlier deletion)
				$query
					->and_where('last_update_dttm', '=', $data['prev_update_dttm'])
					->and_where('to_delete', '=', 1)
				;
			}
			
			// Attempt ITEM "Delete"/Undelete 
			$query
				->execute();
			
			// Updates were successful, commit the changes
			$db->commit();
		}
		catch (Database_Exception $e)
		{
      		  
			// Update failed, roll back changes
			$db->rollback();
			
			// Generate system email with appropriate data to track down/recreate error,
			//		redirect user to sign in page, and display error message
			$error_data = array(
				'problem_descr' => 'Collection->update_to_delete()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	
	
	
	// Business logic functions

	public function create_collection($post)
	{
			  
		/*
		/	Purpose: Create new collection for current user
		/
		/	Parms:
		/		'post' >> Submitted form data from New collection view
		/
		/	Returns:
		/		Array containing:
		/			'Clean_Post_Data' >> Array containing cleaned form data
		/			'Success' >> Boolean indicating success/failure of collection creation
		/		[AND]
		/			'Errors' >> Array containing form validation errors
		*/

		$return_arr = array(
			'Success' => FALSE		  
		);
			  
		// Validate Add Collection form entry
		$validation_results_arr = Validation_Collection::form_fields($post);
		if ($validation_results_arr['Success'])
		{
				  			
			// Validation clean, create new collection							
			$collection_data = array(
				'user_id' => Session::instance()->get('user_id')
				,'descr' => $validation_results_arr['Clean_Post_Data']['txtCollectionName']
				,'coll_type_id' => $validation_results_arr['Clean_Post_Data']['selCollectionType']
			);		

			// Perform collection creation
			if ($this->create($collection_data))
			{
				$return_arr['Clean_Post_Data'] = $validation_results_arr['Clean_Post_Data'];
				
				// All is well!
				$return_arr['Success'] = TRUE;
			}
		}
		else
		{
			$return_arr['Errors'] = $validation_results_arr['Errors'];
		}
		
		return $return_arr;
		
	}

	public function delete_collection($collection_id, $to_delete)
	{

		/*
		/	Purpose: Delete/undelete specific collection for current user
		/
		/	Parms:
		/		'collection_id' >> Collection ID to be deleted/undeleted
		/		'to_delete' >> Flag indicating whether to delete or undelete collection
		/
		/	Returns:
		/		Array containing:
		/			'Success' >> Boolean indicating success/failure of collection delete/undelete
		*/

		$return_arr = array(
			'Success' => FALSE		  
		);
		
		// Read one collection's attributes and items
		$collection_data = array(
			'user_id' => Session::instance()->get('user_id')
			,'collection_id' => $collection_id
			,'to_delete' => $to_delete
			,'last_update_dttm' => date('Y-m-d H:i:s')
		);
		$coll_items_arr = $this->read_one_colls_items($collection_data);
		if ($coll_items_arr['Success'])
		{

			// Found collection AND its items (if any) AND they belong to current user
			
			// Set parm for use by Undo call prior to update to collection
			$collection_data['prev_update_dttm'] = $coll_items_arr['Collection']['last_update_dttm'];
			
			$coll_and_items_deleted_arr = $this->update_to_delete($collection_data);
			if ($coll_and_items_deleted_arr['Rows_Affected'] == 1)
			{
					
				// Delete or undelete succeeded!

				// All is well!
				$return_arr['Success'] = TRUE;
			}	  
		}
		
		return $return_arr;
		
	}
	
	public function edit_collection($collection_id)
	{
			  
		/*
		/	Purpose: Edit specific collection for current user
		/
		/	Parms:
		/		'collection_id' >> Collection ID to edit
		/
		/	Returns:
		/		Array containing:
		/			'Success' >> Boolean indicating success/failure of collection's existence
		*/
			  
		$return_arr = array(
			'Success' => FALSE		  
		);
		
		$collection_data = array(
			'user_id' => Session::instance()->get('user_id')
			,'collection_id' => $collection_id
		);
		$coll_items_arr = $this->read_exists($collection_data);
		if ($coll_items_arr['Rows_Affected'] == 1)
		{

			// All is well!
			$return_arr['Success'] = TRUE;	  
		}
		
		return $return_arr;
		
	}
	
	public function read_one_colls_items($data)
	{
			  
		/*
		/	Purpose: Read one specific collection, including items contained in it
		/
		/	Parms:
		/		Array containing:
		/			'user_id' >> User ID to return collection for
		/			'collection_id' >> Collection ID of collection to return
		/
		/	Returns:
		/		Array containing:
		/			'Success' >> Boolean indicating success/failure of collection read
		/		[AND]
		/			'Collection' >> Array containing collection's attributes
		/			'Items' >> Array containing items in collection
		*/

		$return_arr = array(
			'Success' => FALSE		  
		);

		// Read one collection's attributes
		$collection_rowset = $this->read_one($data);
		if ($collection_rowset['Rows_Affected'] == 1)
		{

			// Collection exists AND belongs to current user
				
			// Preserve collection's attributes
			$return_arr['Collection'] = $collection_rowset['Rows'][0];
					  
			// Read collection's items
			$item_rowset = Model::factory('Item')->read_one_collection($data);
						  
			// NO DB Exception, if we made it this far
			$return_arr['Items'] = $item_rowset['Rows'];
			
			// All is well!
			$return_arr['Success'] = TRUE;
		}
		// else NOOP - Nothing to do here as no rows returned because EITHER this 
		//		collection doesn't exist OR it doesn't belong to current user
		
		return $return_arr;
		
	}
		
	public function read_one_item($data)
	{
			  
		/*
		/	Purpose: Read one specific collection, including one specific item contained in 
		/					it
		/
		/	Parms:
		/		Array containing:
		/			'user_id' >> User ID to return collection for
		/			'collection_id' >> Collection ID of collection to return
		/			'seq' >> Item ID of item to return
		/
		/	Returns:
		/		Array containing:
		/			'Success' >> Boolean indicating success/failure of collection read
		/		[AND]
		/			'Collection' >> Array containing collection's attributes
		/			'Item' >> Array containing item in collection
		*/

		$return_arr = array(
			'Success' => FALSE		  
		);

		// Read one collection's attributes
		$collection_rowset = $this->read_one($data);
		if ($collection_rowset['Rows_Affected'] == 1)
		{

			// Collection exists AND belongs to current user
				
			// Preserve collection's attributes
			$return_arr['Collection'] = $collection_rowset['Rows'][0];

			// Read collection's ITEM
			$item_rowset = Model::factory('Item')->read_one($data);
			
			// NO DB Exception, if we made it this far
					
			if ($item_rowset['Rows_Affected'] == 1)
			{
				$return_arr['Item'] = $item_rowset['Rows'][0];
				
				// All is well!
				$return_arr['Success'] = TRUE;
			}
			// else NOOP - Nothing to do here as no ITEM row returned
		}
		// else NOOP - Nothing to do here as no COLLECTION row returned
		
		return $return_arr;		
		
	}

	public function update_collection($post, $collection_id)
	{
			  
		/*
		/	Purpose: Update specific collection for current user
		/
		/	Parms:
		/		'post' >> Submitted form data from Edit Collection view
		/		'collection_id' >> Collection ID to be updated
		/
		/	Returns:
		/		Array containing:
		/			'Clean_Post_Data' >> Array containing cleaned form data
		/			'Success' >> Boolean indicating success/failure of collection update
		/		[AND]
		/			'Errors' >> Array containing form validation errors
		*/

		$return_arr = array(
			'Success' => FALSE		  
		);
			  
		// Validate Edit Collection form entry
		$validation_results_arr = Validation_Collection::form_fields($post);

		if ($validation_results_arr['Success'])
		{
			
			// Validation clean, update existing collection							
			$collection_data = array(
				'user_id' => Session::instance()->get('user_id')
				,'collection_id' => $collection_id
				,'descr' => $validation_results_arr['Clean_Post_Data']['txtCollectionName']
				,'coll_type_id' => $validation_results_arr['Clean_Post_Data']['selCollectionType']
			);			
			if ($this->update($collection_data))
			{
				$return_arr['Clean_Post_Data'] = $validation_results_arr['Clean_Post_Data'];
				
				// All is well!
				$return_arr['Success'] = TRUE;
			}
		}
		else
		{
			$return_arr['Errors'] = $validation_results_arr['Errors'];
		}
		
		return $return_arr;	
		
	}
	
	

	// Static functions	

	
	
} // End Model_Collection
