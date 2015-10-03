<?php defined('SYSPATH') or die('No direct script access.');

class Model_Item extends Model_Database {
		  
	public function __construct()  
	{

		/*
		/	Purpose: Create item model
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		parent::__construct();
		$this->table_name = 'item';  
		
		$this->table_cols = array(
			'collection_id'
			,'seq'
			,'descr'
			,'status'
			,'to_delete'
			,'last_update_dttm'
		);
		
	}
	
	
	
	// SQL functions

	public function create($data)
	{

		/*
		/	Purpose: Create new item row in database
		/
		/	Parms:
		/		Array containing:
		/			'collection_id' >> Collection ID that new item belongs to
		/			'seq' >> New item's sequence in collection
		/			'descr' >> New item's description
		/			'status' >> New item's status
		/		
		/	Returns:
		/		Array containing:
		/			'Row_Created_ID' >> New item's ID
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
						$data['collection_id']
						,$data['seq']
						,$data['descr']
						,$data['status']
				
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
				'problem_descr' => 'Item->create()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	
	public function read_exists($data)
	{
		
		/*
		/	Purpose: Confirm/refute existence of specific item within a specific collection
		/
		/	Parms:
		/		Array containing:
		/			'collection_id' >> Collection ID that item belongs to
		/			'seq' >> Seq of item to find
		/
		/	Returns:
		/		Array containing:
		/			'Rows_Affected' >> Number of item rows (0 or 1) matching specified criteria
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();

		try
		{
			$return_arr['Rows_Affected'] = 
				DB::select(array(DB::expr('COUNT(seq)'), 'total_count'))
					->from($this->table_name)
					->where('collection_id', '=', $data['collection_id'])
					->and_where('seq', '=', $data['seq'])
					->execute()
					->get('total_count', 0)
			;
		}  
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'Item->read_exists()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}

	public function read_one($data)
	{
			  
		/*
		/	Purpose: Read one specific item for specified collection
		/
		/	Parms:
		/		Array containing:
		/			'collection_id' >> Collection ID of collection to return
		/			'seq' >> Seq of item, within collection, to return
		/
		/	Returns:
		/		Array containing:
		/			'Rows' >> Array containing specific item contained in specified collection
		/		AND
		/			'Rows_Affected' >> Number of item rows in 'Rows'
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try
		{
			$return_arr['Rows'] = 
				DB::select()
					->from($this->table_name)
					->where('collection_id', '=', $data['collection_id'])
					->and_where('seq', '=', $data['seq'])
					->execute()
			;
			$return_arr['Rows_Affected'] = count($return_arr['Rows']);
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'Item->read_one()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	
	public function read_one_collection($data)
	{
			  
		/*
		/	Purpose: Read all items for specified collection
		/
		/	Parms:
		/		Array containing:
		/			'collection_id' >> Collection ID of collection to return
		/
		/	Returns:
		/		Array containing:
		/			'Rows' >> Array containing items contained in specified collection
		/		AND
		/			'Rows_Affected' >> Number of collection rows in 'Rows'
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try 
		{		
			$return_arr['Rows'] = 
				DB::select()
					->from($this->table_name)
					->where('collection_id', '=', $data['collection_id'])
					->and_where('to_delete', '=', 0)
					->order_by('seq')
					->execute()
			;		
			$return_arr['Rows_Affected'] = count($return_arr['Rows']);
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'Item->read_one_collection()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}

	public function update($data)
	{
			  
		/*
		/	Purpose: Updates specified item
		/
		/	Parms:
		/		Array containing:
		/			'collection_id' >> ID of collection to update
		/			'seq' >> Item's updated seq
		/			'descr' >> Item's updated description
		/			'status' >> Item's updated status
		/			'prev_seq' >> (Old) seq of item, within collection, to update
		/
		/	Returns:
		/		Array containing:
		/			'Rows_Affected' >> Number of item rows affected by UPDATE
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try
		{
			$return_arr['Rows_Affected'] = 
				DB::update($this->table_name)
					->set(array(
						'seq' => $data['seq']
						,'descr' => $data['descr']
						,'status' => $data['status']
					))
					->where('collection_id', '=', $data['collection_id'])
					->and_where('seq', '=', $data['prev_seq'])
					->execute()
			;
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'Item->update()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	
	public function update_incr_seq($data)
	{

		/*
		/	Purpose: Increments seq (by 1) of specified item in specific collection
		/
		/	Parms:
		/		Array containing:
		/			'collection_id' >> ID of collection to update
		/			'old_seq' >> (Old) seq of item, within collection, to update
		/
		/	Returns:
		/		Array containing:
		/			'Rows_Affected' >> Number of item rows affected by UPDATE
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try
		{
			$return_arr['Rows_Affected'] = 
				DB::update($this->table_name)
					->set(array(
						'seq' => DB::expr('seq + 1')
					))
					->where('collection_id', '=', $data['collection_id'])
					->and_where('seq', '=', $data['old_seq'])
					->execute()
			;
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'Item->update_incr_seq()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	
	public function update_to_delete($data)
	{

		/*
		/	Purpose: Flags (or unflags) specified item, in specific collection, for deletion
		/
		/	Parms:
		/		Array containing:
		/			'collection_id' >> ID of collection to delete/undelete from
		/			'seq' >> Item to delete/undelete
		/			'to_delete' >> Flag indicating whether to delete or undelete item
		/			'last_update_dttm' >> Item's last update DATETIME
		/
		/	Returns:
		/		Array containing:
		/			'Rows_Affected' >> Number of item rows affected by UPDATE
		/		OR
		/			Generates fatal error, sending error notif to admin
		*/

		$return_arr = array();
		
		try
		{
			$return_arr['Rows_Affected'] = 
				DB::update($this->table_name)
					->set(array(
						'to_delete' => $data['to_delete']
						,'last_update_dttm' => $data['last_update_dttm'] 
					))
					->where('collection_id', '=', $data['collection_id'])
					->and_where('seq', '=', $data['seq'])
					->execute()
			;
		}
		catch (Database_Exception $e)
		{
		
			// Generate system email with appropriate data to track down/recreate error
			$error_data = array(
				'problem_descr' => 'Item->update_to_delete()'
					.'<br />'.Database_Exception::text($e)
			);							
			AdminErrorNotif::fatal($error_data);		
		}
		
		return $return_arr;
		
	}
	
	
   	
	// Business logic functions

	public function create_item($post, $collection_id)
	{
			  
		/*
		/	Purpose: Create new item after first confirming collection belongs to current user
		/
		/	Parms:
		/		'post' >> Submitted form data from New Item view
		/		'collection_id' >> Collection ID to which item is to be added
		/
		/	Returns:
		/		Array containing:
		/			'Clean_Post_Data' >> Array containing cleaned form data
		/			'Success' >> Boolean indicating success/failure of item creation
		/		[AND]
		/			'Errors' >> Array containing form validation errors
		*/

		$return_arr = array(
			'Success' => FALSE		  
		);
		
		// Validate Add Item form entry
		$validation_results_arr = Validation_Item::form_fields($post);
		if ($validation_results_arr['Success'])
		{
				  			
			// Validation clean							
			  
			// Select collection that item is attempting to be added to (to ensure it is a 
			//		collection owned by the current user)
			$collection_data = array(
				'user_id' => Session::instance()->get('user_id')
				,'collection_id' => $collection_id
			);
			$collection_row_exists = Model::factory('Collection')->read_exists($collection_data);
			if ($collection_row_exists['Rows_Affected'] == 1)
			{
			
				$form_item_data = array(
					'user_id' => Session::instance()->get('user_id')
					,'collection_id' => $collection_id
					,'seq' => $validation_results_arr['Clean_Post_Data']['txtItemSeq']
					,'descr' => $validation_results_arr['Clean_Post_Data']['txtItemName']
					,'status' => $validation_results_arr['Clean_Post_Data']['selItemStatus']
				);	
				
				// Perform item select
				$item_rowset = $this->read_exists($form_item_data);
				if ($item_rowset['Rows_Affected'] == 0)
				{
					
					// No existing item with specified seq value
				  
					// Perform item creation
					$item_created_arr = $this->create($form_item_data);
					$return_arr['Clean_Post_Data'] = $validation_results_arr['Clean_Post_Data'];

					// All is well!
					$return_arr['Success'] = TRUE;
				}		 
				else
				{
					
					// There IS an existing item with specified seq value, need to re-seq
								
					$item_arr = $this->read_one_collection($form_item_data);
						
					$item_seq_arr = array();
					foreach ($item_arr['Rows'] as $item)
					{
						$item_seq_arr[$item['seq']] = $item;				
					}
				
					// Find max consecutive existing seq
					// Start looking at index immediately above target
					$max_consec_seq = $form_item_data['seq'] + 1;
					while (array_key_exists($max_consec_seq, $item_seq_arr))
					{
						$max_consec_seq++;	// NOTE: This will end up 1 more than it should be - adjust in for loop below
					}
				
					// Starting at max existing index, go back down to seq user is attempting to insert,
					//		incrementing by 1 each existing index
					$item_data = array(
						'collection_id' => $form_item_data['collection_id']	  
					);		
					for ($i = $max_consec_seq - 1; $i >= $form_item_data['seq']; $i--)
					{
						$item_data['old_seq'] = $i;
						$item_updated_arr = $this->update_incr_seq($item_data);
					}
					  
					// Perform item creation
					$item_created_arr = $this->create($form_item_data);
					$return_arr['Clean_Post_Data'] = $validation_results_arr['Clean_Post_Data'];
					
					// All is well!
					$return_arr['Success'] = TRUE;					  
				}
			}
			// else NOOP - Collection doesn't exist or doesn't belong to current user
		}
		else
		{
			$return_arr['Errors'] = $validation_results_arr['Errors'];
		}
		
		return $return_arr;
		
	}
	
	public function delete_item($collection_id, $seq, $to_delete)
	{
			  
		/*
		/	Purpose: Delete/undelete item after first confirming collection belongs to current user
		/
		/	Parms:
		/		'collection_id' >> Collection ID from/to which item is to be deleted/undeleted
		/		'seq' >> Item's sequence in collection
		/		'to_delete' >> Flag indicating whether to delete or undelete item
		/
		/	Returns:
		/		Array containing:
		/			'Success' >> Boolean indicating success/failure of item delete/undelete
		*/			  

		$return_arr = array(
			'Success' => FALSE		  
		);

		// Select collection that item is attempting to "delete"/"undelete" from/to (to 
		//		ensure it is a collection owned by the current user)
		$collection_data = array(
			'user_id' => Session::instance()->get('user_id')
			,'collection_id' => $collection_id
		);
		$collection_row_exists = Model::factory('Collection')->read_exists($collection_data);
		if ($collection_row_exists['Rows_Affected'] == 1)
		{
			
			// Perform item "delete"/"undelete"
			$item_data = array(
				'user_id' => Session::instance()->get('user_id')
				,'collection_id' => $collection_id
				,'seq' => $seq
				,'to_delete' => $to_delete
				,'last_update_dttm' => date('Y-m-d H:i:s')
			);
			$item_deleted_arr = $this->update_to_delete($item_data);
			if ($item_deleted_arr['Rows_Affected'] == 1)
			{	

				// All is well!
				$return_arr['Success'] = TRUE;
			}
			// else NOOP - Attempt to delete non-existent item in current user's collection			
		}
		// else NOOP - Bad URL
		
		return $return_arr;
		
	}
	
	public function edit_item($collection_id, $seq)
	{
			  
		/*
		/	Purpose: Edit specific item after first confirming collection belongs to current user
		/
		/	Parms:
		/		'collection_id' >> Collection ID of item to be edited
		/		'seq' >> Item's sequence in collection
		/
		/	Returns:
		/		Array containing:
		/			'Success' >> Boolean indicating success/failure of collection's (and any 
		/								associated items) existence
		/		[AND]
		/			'Collection' >> Array containing collection's attributes
		/			'Item' >> Array containing item in collection
		*/

		$return_arr = array(
			'Success' => FALSE		  
		);
		
		$coll_and_item_data = array(
			'user_id' => Session::instance()->get('user_id')
			,'collection_id' => $collection_id
			,'seq' => $seq
		);
		$coll_and_item_arr = Model::factory('Collection')->read_one_item($coll_and_item_data);
		if ($coll_and_item_arr['Success'])
		{
			$return_arr = $coll_and_item_arr;	  
		}
		
		return $return_arr;
		
	}
	
	public function update_item($post, $collection_id, $seq)
	{

		/*
		/	Purpose: Update an existing item after first confirming collection belongs to current user
		/
		/	Parms:
		/		'post' >> Submitted form data from Edit Item view
		/		'collection_id' >> Collection ID to which item belongs
		/		'seq' >> Item's current sequence in collection
		/
		/	Returns:
		/		Array containing:
		/			'Clean_Post_Data' >> Array containing cleaned form data
		/			'Success' >> Boolean indicating success/failure of item update
		/		[AND]
		/			'Errors' >> Array containing form validation errors
		*/			

		$return_arr = array(
			'Success' => FALSE		  
		);
		
		// Validate Add Item form entry
		$validation_results_arr = Validation_Item::form_fields($post);
		if ($validation_results_arr['Success'])
		{
			  
			// Validation clean
			
			// Select collection that item is attempting to be added to (to ensure it is a 
			//		collection owned by the current user)
			$collection_data = array(
				'user_id' => Session::instance()->get('user_id')
				,'collection_id' => $collection_id
			);
			$collection_row_exists = Model::factory('Collection')->read_exists($collection_data);
			if ($collection_row_exists['Rows_Affected'] == 1)
			{
				
				// Update existing item							
				$form_item_data = array(
					'user_id' => Session::instance()->get('user_id')
					,'collection_id' => $collection_id
					,'prev_seq' => $seq
					,'seq' => $validation_results_arr['Clean_Post_Data']['txtItemSeq']
					,'descr' => $validation_results_arr['Clean_Post_Data']['txtItemName']
					,'status' => $validation_results_arr['Clean_Post_Data']['selItemStatus']
				);
			
				if ($form_item_data['seq'] == $form_item_data['prev_seq'])
				{
					
					// Seq has NOT changed, perform item update
					$item_updated_arr = $this->update($form_item_data);
					$return_arr['Clean_Post_Data'] = $validation_results_arr['Clean_Post_Data'];

					// All is well!
					$return_arr['Success'] = TRUE; 
				}
				else
				{
				  
					//	Seq HAS changed
				
					// Perform item select
					$item_rowset = $this->read_exists($form_item_data);
					if ($item_rowset['Rows_Affected'] == 0)
					{
					  
						// No existing item with specified seq value
				  
						// Perform item update
						$item_updated_arr = $this->update($form_item_data);
						$return_arr['Clean_Post_Data'] = $validation_results_arr['Clean_Post_Data'];

						// All is well!
						$return_arr['Success'] = TRUE;
					}
					else
					{
				
						// There IS an existing item with specified seq value, need to re-seq				

						$item_arr = $this->read_one_collection($form_item_data);	
						
						$item_seq_arr = array();
						foreach ($item_arr['Rows'] as $item)
						{
							$item_seq_arr[$item['seq']] = $item;				
						}
					
						// Find max consecutive existing seq!
						// Start looking at index immediately above target
						$max_consec_seq = $form_item_data['seq'] + 1;					
						while (array_key_exists($max_consec_seq, $item_seq_arr))
						{
							$max_consec_seq++;	// NOTE: This will end up 1 more than it should be - adjust in for loop below
						}
					
						// Starting at max existing index, go back down to seq user is attempting to insert,
						//		incrementing by 1 each existing index					
						$item_data = array(
							'collection_id' => $form_item_data['collection_id']	  
						);
						for ($i = $max_consec_seq - 1; $i >= $form_item_data['seq']; $i--)
						{
							$item_data['old_seq'] = $i;
							$item_updated_arr = $this->update_incr_seq($item_data);
						} // for ($i = $max_consec_seq - 1; $i >= $data['seq']; $i--)
				
						// Perform item update
						if (($form_item_data['seq'] < $form_item_data['prev_seq']) AND ($max_consec_seq >= $form_item_data['prev_seq']))
						{
					
							// Need to adjust source seq value to account for increment
							$form_item_data['prev_seq']++;
						}
						$final_item_updated_arr = $this->update($form_item_data);
						$return_arr['Clean_Post_Data'] = $validation_results_arr['Clean_Post_Data'];
						
						// All is well!
						$return_arr['Success'] = TRUE;			
					}
				}	
			}
			// else NOOP - Collection doesn't exist or doesn't belong to current user
		}
		else
		{
			$return_arr['Errors'] = $validation_results_arr['Errors'];
		}
		
		return $return_arr;
		
	}
	

	
	// Static functions
	


} // End Model_Item
