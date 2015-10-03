<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Collection extends Controller_Template_Sitepage {

	public function before()
	{
		
		/*
		/	Purpose: Ensure user is signed in before actions in controller are called
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		parent::before();  	  
		if ( ! (Session::instance()->get('user_id')))
		{
					
			// User is NOT signed in, redirect to Sign In page
			$this->redirect('User');			
		}
		
	}

	public function action_index()
	{

		/*
		/	Purpose: Default action - routes to view action
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		// Route to view action, by default
		$this->action_view();
		
	}

	public function action_add()
	{		
		
		/*
		/	Purpose: Action to handle "add collection" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		// Grab $_POST array from submitted form
		$post = $this->request->post();
		
		if (isset($post['btnAdd']))
		{
		
			// Add button was pressed
			
			$collection_created_arr = Model::factory('Collection')->create_collection($post);
			if ($collection_created_arr['Success'])
			{

				// Send message indicating successful collection creation
				Session::instance()->set('message', "You've added new collection \"".$collection_created_arr['Clean_Post_Data']['txtCollectionName']."\"!");				

				// Redirect to view action 
				$this->redirect('Collection/view');
			}
			else
			{

				// Validation failed, display form with custom error text
				$accordions_open = array(
					'add_collection' => TRUE
					,'collections' => FALSE
				);
				$this->template->content = ViewBuilder::factory('Page')->view_collections($accordions_open, $this->template, $post, $collection_created_arr['Errors']);
			}
		} // if (isset($post['btnAdd']))
		else	// This "else" is required so that failed validation (above) won't auto redirect!
		{
		
			// Redirect to view action	// Bad URL
			$this->redirect('Collection/view');
		}	
		
	}
	
	public function action_edit()
	{

		/*
		/	Purpose: Action to handle "edit collection" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		// Grab selected collection ID from URL
		$collection_id = $this->request->param('collection');
	
		// Determine if this collection exists AND belongs to current user
		$collection_exists_arr = Model::factory('Collection')->edit_collection($collection_id);
		if ($collection_exists_arr['Success'])
		{
		
			// Show selected collection
			$accordions_open = array(
				'add_item' => FALSE
			);
			$posts = array(
				'add_item' => NULL
				,'edit_collection' => NULL
			);
			$this->template->content = ViewBuilder::factory('Page')->edit_collection($accordions_open, $collection_id, $this->template, $posts, $errors = array());
		}
		else
		{

			// Redirect to view action 
			$this->redirect('Collection/view');				  
		}
		
	}
	
	public function action_undo_delete()
	{
		
		/*
		/	Purpose: Action to handle "undo delete collection" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		// Undo link was clicked

		// Grab selected collection ID from URL
		$collection_id = $this->request->param('collection');

		// "Undelete" collection (and its items)			
		$collection_undeleted_arr = Model::factory('Collection')->delete_collection($collection_id, $to_delete = 0);
		if ($collection_undeleted_arr['Success']) 
		{
			
			// Send message indicating successful collection UNdelete
			Session::instance()->set('message', "You've undone collection delete.");				  
		}
		// else NOOP - This did not undelete anything because of a bad URL
				
		// Redirect to view action
		$this->redirect('Collection/view');
		
	}
	
	public function action_update()
	{
			  
		/*
		/	Purpose: Action to handle "update collection" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		// Grab selected collection ID from URL
		$collection_id = $this->request->param('collection');

		// Grab $_POST array from submitted form
		$post = $this->request->post();
		
		if (isset($post['btnUpdate']))
		{	
				  
			// Update button was pressed
				  
			$collection_updated_arr = Model::factory('Collection')->update_collection($post, $collection_id);
			if ($collection_updated_arr['Success'])
			{
					
				// Send message indicating successful collection update
				Session::instance()->set('message', "You've updated collection \"".$collection_updated_arr['Clean_Post_Data']['txtCollectionName']."\"!");				
				
				// Redirect to edit action 
				$this->redirect('Collection/edit/'.$collection_id);	
			} // if ($collection_updated_arr['Success'])
			else
			{
					  
				// Validation failed, display form with custom error text				
				$accordions_open = array(
					'add_item' => FALSE
				);
				$posts = array(
					'add_item' => NULL
					,'edit_collection' => $post
				);
				$this->template->content = ViewBuilder::factory('Page')->edit_collection($accordions_open, $collection_id, $this->template, $posts, $collection_updated_arr['Errors']);
			}
		} // if (isset($post['btnUpdate']))
		elseif (isset($post['btnDelete']))
		{
      		  
			// Delete button was pressed
			
			$collection_deleted_arr = Model::factory('Collection')->delete_collection($collection_id, $to_delete = 1);
			if ($collection_deleted_arr['Success']) 
			{
      			  
				// Send message indicating successful collection delete, giving option to Undo
				Session::instance()->set('warning', "You've deleted collection \"" 
					.$post['txtCollectionName']."\" (and all of its items)! " 
					.HTML::anchor('Collection/undo_delete/'.$collection_id, 'Click here to Undo.')
				);      			  
			} // if ($collection_deleted_arr['Success'])
			// else NOOP - This did not delete anything because of a bad URL
			
			// Redirect to view action
			$this->redirect('Collection/view');					  
		} // elseif (isset($post['btnDelete'])) 
		else
		{
      		  
			// Bad URL (i.e. someone went to /Collection/update manually)

			// Redirect to view action
			$this->redirect('Collection/view');
		}
		
	}	
	
	public function action_view()
	{
			  		
		/*
		/	Purpose: Action to handle "view collections" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		// Show all of current user's collections
		$accordions_open = array(
			'add_collection' => FALSE
			,'collections' => TRUE
		);
		$this->template->content = ViewBuilder::factory('Page')->view_collections($accordions_open, $this->template, NULL, $errors = array());
		
	}

} // End Collection
