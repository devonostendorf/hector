<?php defined('SYSPATH') or die('No direct script access.');

class ViewBuilder_CollectionView {

	public function add_collection($accordion_open, $collection_type_sel_arr, $post_or_null, $errors)
	{

		/*
		/	Purpose: Build Add Collection view
		/
		/	Parms:
		/		'accordion_open' >> Boolean indicating whether view's accordion should default to open
		/		'collection_type_sel_arr' >> Array containing selectable collection types
		/		'post_or_null' >> Submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		View to render
		*/			
		      
		$content =
 
			// Include message block
			View::factory('Message_Block')
      	
			.View::factory('Accordion_Start')
				->set('accordion_id', 'add_collection')

			// Render Add Collection view
			.View::factory('Add_Collection')
				->set('collection_type_sel_arr', $collection_type_sel_arr)
				->set('accordion_group_open', $accordion_open)
				->set('page_description', 'New Collection')
				->set('post', $post_or_null)
				->set('errors', $errors)
      		
			.View::factory('Accordion_End')
		;
     
		return $content;
		
	}

	public function collections($accordion_open, $collection_arr)
	{
		
		/*
		/	Purpose: Build Collections view
		/
		/	Parms:
		/		'accordion_open' >> Boolean indicating whether view's accordion should default to open
		/		'collection_arr' >> Array containing user's collections
		/
		/	Returns:
		/		View to render
		*/			
			  		
		$content = 
		
			View::factory('Accordion_Start')
				->set('accordion_id', 'collections')

			// Render Collections view
			.View::factory('Collections')
				->set('collection_arr', $collection_arr['Rows'])
				->set('accordion_group_open', $accordion_open)
				->set('page_description', 'Collections')
  
			.View::factory('Accordion_End')
		;
		
		return $content;
		
	}

	public function edit_collection($collection_id, $collection_type_sel_arr, $coll_and_items_arr, $post_or_null, $errors)
	{
			  
		/*
		/	Purpose: Build Edit Collection view
		/
		/	Parms:
		/		'collection_id' >> Collection ID to edit
		/		'collection_type_sel_arr' >> Array containing selectable collection types
		/		'coll_and_items_arr' >> Array containing user's selected collection (and its items)
		/		'post_or_null' >> Submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		View to render
		*/			
      
		$content =
 
			// Render message block
			View::factory('Message_Block')
       	
			// Render Edit Collection view
			.View::factory('Edit_Collection')
				->set('collection_type_sel_arr', $collection_type_sel_arr)
				->set('collection_arr', $coll_and_items_arr['Collection'])
				->set('page_description', 'Edit Collection')
				->set('post', $post_or_null)
				->set('errors', $errors)
		; 
     	
		return $content;
		
	}
		
} // End ViewBuilder_CollectionView
