<?php defined('SYSPATH') or die('No direct script access.');

class ViewBuilder_ItemView {

	public function add_item($collection_id, $item_status_sel_arr, $accordion_open, $post_or_null, $errors)
	{
			  
		/*
		/	Purpose: Build Add Item view
		/
		/	Parms:
		/		'collection_id' >> Collection ID of item to edit
		/		'item_status_sel_arr' >> Array containing selectable item statuses
		/		'accordion_open' >> Boolean indicating whether view's accordion should default to open
		/		'post_or_null' >> Submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		View to render
		*/			

		$content =

			View::factory('Accordion_Start')
				->set('accordion_id', 'add_item')

			// Render Add Item view
			.View::factory('Add_Item')
				->set('collection_id', $collection_id)
				->set('item_status_sel_arr', $item_status_sel_arr)
				->set('accordion_group_open', $accordion_open)
				->set('page_description', 'New Item')
				->set('post', $post_or_null)
				->set('errors', $errors)
      		
			.View::factory('Accordion_End')
		;
      
		return $content;
		
	}

	public function edit_item($collection_id, $seq, $item_status_sel_arr, $coll_and_item_arr, $post_or_null, $errors)
	{
	
		/*
		/	Purpose: Build Edit Item view
		/
		/	Parms:
		/		'collection_id' >> Collection ID of item to edit
		/		'seq' >> Item's sequence in collection
		/		'item_status_sel_arr' >> Array containing selectable item statuses
		/		'coll_and_item_arr' >> Array containing user's selected collection and specific item
		/		'post_or_null' >> Submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		View to render
		*/			
			  
		$content =
		
			// Render message block
			View::factory('Message_Block')
      	
			// Render Edit Item view
			.View::factory('Edit_Item')
				->set('collection_id', $collection_id)
				->set('seq', $seq)
				->set('item_status_sel_arr', $item_status_sel_arr)
				->set('coll_and_item_arr', $coll_and_item_arr)
				->set('page_description', 'Edit Item')
				->set('post', $post_or_null)
				->set('errors', $errors)
		;      
		
		return $content;
		
	}

	public function items($collection_id, $coll_and_items_arr, $item_status_sel_arr)
	{

		/*
		/	Purpose: Build Items view
		/
		/	Parms:
		/		'collection_id' >> Collection ID of items
		/		'coll_and_items_arr' >> Array containing user's selected collection (and its items)
		/		'item_status_sel_arr' >> Array containing selectable item statuses
		/
		/	Returns:
		/		View to render
		*/			

		$content =
      			 
			View::factory('Accordion_Start')
				->set('accordion_id', 'items')

			// Render Items view
			.View::factory('Items')
				->set('items_arr', $coll_and_items_arr['Items'])
				->set('item_status_sel_arr', $item_status_sel_arr)
				->set('accordion_group_open', TRUE)
				->set('page_description', 'Items')
  
			.View::factory('Accordion_End')
		;
      
		return $content;
		
	}

} // End ViewBuilder_ItemView
