<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Item extends Controller_Template_Sitepage {
	
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
		/	Purpose: Default action - redirects to collection controller
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		// Redirect to Collection's default action, by default
		$this->redirect('Collection');	
		
	}

	public function action_add()
	{		
		
		/*
		/	Purpose: Action to handle "add item" requests
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
		
		if (isset($post['btnAdd']))
		{
		
			// Add button was pressed
			
			$item_created_arr = Model::factory('Item')->create_item($post, $collection_id);
			if ($item_created_arr['Success'])
			{
				
				// Send message indicating successful item creation
				Session::instance()->set('message', "You've added new item \"".$item_created_arr['Clean_Post_Data']['txtItemName']."\"!");
			
				// Redirect to Collection/edit action 
				$this->redirect('Collection/edit/'.$collection_id);			
			}
			else
			{
				
				// Validation failed, display form with custom error text
				$accordions_open = array(
					'add_item' => TRUE
				);
				$posts = array(
					'add_item' => $post
					,'edit_collection' => NULL
				);
				$this->template->content = ViewBuilder::factory('Page')->edit_collection($accordions_open, $collection_id, $this->template, $posts, $item_created_arr['Errors']);
			}		
		} // if (isset($post['btnAdd']))
		else	// This "else" is required so that failed validation (above) won't auto redirect!
		{
		
			// Redirect to Collection/edit action 
			$this->redirect('Collection/edit/'.$collection_id);			
		}	
		
	}
	
	public function action_edit()
	{

		/*
		/	Purpose: Action to handle "edit item" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		// Grab selected collection ID, seq (item ID) from URL
		$collection_id = $this->request->param('collection');
		$seq = $this->request->param('seq');

		// Determine if this item exists AND belongs to current user
		$coll_and_item_arr = Model::factory('Item')->edit_item($collection_id, $seq);
		if ($coll_and_item_arr['Success'])
		{
			
			// Show selected item
			$posts = array(
				'edit_item' => NULL
			);
 			$this->template->content = ViewBuilder::factory('Page')->edit_item($collection_id, $seq, $this->template, $coll_and_item_arr, $posts, $errors = array());				  
		}
		else
		{
     		
			// Redirect to Collection/view action 
			$this->redirect('Collection/view');	
		}
		
	}
	
	public function action_undo_delete()
	{
		
		/*
		/	Purpose: Action to handle "undo delete item" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/
		
		// Undo link was clicked

		// Grab selected collection ID, seq (item ID) from URL
		$collection_id = $this->request->param('collection');
		$seq = $this->request->param('seq');
		
		// "Undelete" item
		$item_undeleted_arr = Model::factory('Item')->delete_item($collection_id, $seq, $to_delete = 0);
		if ($item_undeleted_arr['Success'])
		{
       	
			// Send message indicating successful item UNdelete
			Session::instance()->set('message', "You've undone item delete.");				  
		}
		// else NOOP - This did not undelete anything because of a bad URL
		
		// Redirect to Collection/edit action 
		$this->redirect('Collection/edit/' . $collection_id);
		
	}	

	public function action_update()
	{
			  
		/*
		/	Purpose: Action to handle "update item" requests
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		// Grab selected collection ID, seq (item ID) from URL
		$collection_id = $this->request->param('collection');
		$seq = $this->request->param('seq');
				  
		// Grab $_POST array from submitted form
		$post = $this->request->post();
		
		if (isset($post['btnUpdate']))
		{
			
			// Update button was pressed
			
			$item_updated_arr = Model::factory('Item')->update_item($post, $collection_id, $seq);
			if ($item_updated_arr['Success'])
			{
					
				// Send message indicating successful item update
				Session::instance()->set('message', "You've updated item \"".$item_updated_arr['Clean_Post_Data']['txtItemName']."\"!");
				
				// Redirect to Collection/edit action 
				$this->redirect('Collection/edit/'.$collection_id);
			}
			else
			{
					  
				// Validation failed
     		
				// Determine if this item exists AND belongs to current user
				$coll_and_item_arr = Model::factory('Item')->edit_item($collection_id, $seq);
				if ($coll_and_item_arr['Success'])
				{

					// Display form with custom error text
					$posts = array(
						'edit_item' => $post
					);
					$this->template->content = ViewBuilder::factory('Page')->edit_item($collection_id, $seq, $this->template, $coll_and_item_arr, $posts, $item_updated_arr['Errors']);				  
				}
				else
				{
     		
					// Redirect to Collection/view action 
					$this->redirect('Collection/view');	
				}      		
			}
		} // if (isset($post['btnUpdate']))
		elseif (isset($post['btnDelete']))
		{
			
			// Delete button was pressed
			$item_deleted_arr = Model::factory('Item')->delete_item($collection_id, $seq, $to_delete = 1);
			if ($item_deleted_arr['Success'])
			{
 				
				// Send message indicating successful item delete, giving option to Undo
				Session::instance()->set('warning', "You've deleted item \"" 
					.$post['txtItemName']."\"! " 
					.HTML::anchor('Item/undo_delete/'.$collection_id.'/'.$seq, 'Click here to Undo.') 
				);     		  
			}
			
			// Redirect to Collection/edit action
			$this->redirect('Collection/edit/'.$collection_id);
		} // elseif (isset($post['btnDelete']))
		else
		{
      	
			// Bad URL (i.e. someone went to /Item/update manually

			// Redirect to Collection/edit action
			$this->redirect('Collection/edit/'.$collection_id);
		}
		
	}

} // End Item
