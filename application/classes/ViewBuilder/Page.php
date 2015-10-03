<?php defined('SYSPATH') or die('No direct script access.');

class ViewBuilder_Page {

	public static function create_acct($template, $post_or_null, $errors)
	{

		/*
		/	Purpose: Build Create Acct page
		/
		/	Parms:
		/		'template' >> Array containing page header attributes
		/		'post_or_null' >> Submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		Page to render
		*/		
		
		// Render Create Acct page
		$template->page_description = 'Create Account';
		$template->title .= $template->page_description;
		$template->set_focus = 'frmCreateAcct.txtUsername';
		$content = ViewBuilder::factory('AppUserView')->create_acct($post_or_null, $errors);
		
		return $content;
		
	}
	
	public static function edit_collection($accordions_open, $collection_id, $template, $posts, $errors)
	{
			  
		/*
		/	Purpose: Build Edit Collection page
		/
		/	Parms:
		/		'accordions_open' >> Array, indexed by view, indicating whether that view's accordion should default to open
		/		'collection_id' >> Collection ID to edit
		/		'template' >> Array containing page header attributes
		/		'posts' >> Array, indexed by view, containing either submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		Page to render
		*/			
		
		// Read all collection types
		$collection_type_sel_arr = Model::factory('CollectionType')->read_all_collection_types();
			  			  
		// Read user's selected collection (and its items)
		$coll_and_items_data = array(
			'user_id' => Session::instance()->get('user_id')
			,'collection_id' => $collection_id
		);
		$coll_and_items_arr = Model::factory('Collection')->read_one_colls_items($coll_and_items_data);

		// Read all item statuses
		$item_status_sel_arr = Model::factory('ItemStatus')->read_all_item_statuses();
		
		// Render Items page
		$template->page_description = 'Items';
		$template->title .= $template->page_description;
		$template->navbar = View::factory('Navbar');

		$collection_view = ViewBuilder::factory('CollectionView');
		$item_view = ViewBuilder::factory('ItemView');

		// Edit Collection view
		$content = $collection_view->edit_collection($collection_id, $collection_type_sel_arr, $coll_and_items_arr, $posts['edit_collection'], $errors);
		
		// Add Item view
		$content .= $item_view->add_item($collection_id, $item_status_sel_arr, $accordions_open['add_item'], $posts['add_item'], $errors);

		// Items view
		$content .= $item_view->items($collection_id, $coll_and_items_arr, $item_status_sel_arr);
		
		return $content;
		
	}	
	
	public function edit_item($collection_id, $seq, $template, $coll_and_item_arr, $posts, $errors)
	{

		/*
		/	Purpose: Build Edit Item page
		/
		/	Parms:
		/		'collection_id' >> Collection ID of item to edit
		/		'seq' >> Item's sequence in collection
		/		'template' >> Array containing page header attributes
		/		'coll_and_item_arr' >> Array selected collection and item data
		/		'posts' >> Array, indexed by view, containing either submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		Page to render
		*/			
			  
		// Read all item statuses
		$item_status_sel_arr = Model::factory('ItemStatus')->read_all_item_statuses();  
		
		// Render Edit Item page
		$template->page_description = 'Edit Item';
		$template->title .= $template->page_description;
		$template->navbar = View::factory('Navbar');

		$item_view = ViewBuilder::factory('ItemView');
		
		// Edit Item view
		$content = $item_view->edit_item($collection_id, $seq, $item_status_sel_arr, $coll_and_item_arr, $posts['edit_item'], $errors);

		return $content;
		
	}
	
	public static function forgot_pswd($template, $post_or_null, $errors)
	{
			  
		/*
		/	Purpose: Build Forgot Password page
		/
		/	Parms:
		/		'template' >> Array containing page header attributes
		/		'post_or_null' >> Submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		Page to render
		*/		

		// Render Forgot Password page
		$template->page_description = 'Reset Password';
		$template->title .= $template->page_description;
		$template->set_focus = 'frmForgotPswd.txtUsernameOrEmailAddr';
		$content = ViewBuilder::factory('AppUserView')->forgot_pswd($post_or_null, $errors);
		
		return $content;
		
	}	
	
	public static function informational_message($template, $page_descr, $message)
	{
			  
		/*
		/	Purpose: Build Informational Message page
		/
		/	Parms:
		/		'template' >> Array containing page header attributes
		/		'page_descr' >> Page description
		/		'message' >> Message to display on page
		/
		/	Returns:
		/		Page to render
		*/		
		
		// Render Informational Message page
		$template->page_description = $page_descr;
		$template->title .= $template->page_description;
		$content = ViewBuilder::factory('AppUserView')->informational_message($page_descr, $message);
		
		return $content;
		
	}

	public static function set_password($submit_handler, $template, $post_or_null, $errors)
	{

		/*
		/	Purpose: Build Set Password page
		/
		/	Parms:
		/		'submit_handler' >> Handler to route form to
		/		'template' >> Array containing page header attributes
		/		'post_or_null' >> Submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		Page to render
		*/		
		
		// Render Set Password page
		$template->page_description = 'Set Password';
		$template->title .= $template->page_description;
		$template->set_focus = 'frmSetPswd.pwdNewPassword';
		$content = ViewBuilder::factory('AppUserView')->set_password($submit_handler, $post_or_null, $errors);
		
		return $content;
		
	}
	
	public static function sign_in($template, $post_or_null, $errors)
	{

		/*
		/	Purpose: Build Sign In page
		/
		/	Parms:
		/		'template' >> Array containing page header attributes
		/		'post_or_null' >> Submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		Page to render
		*/		
		
		// Render Sign In page
		$template->page_description = 'Sign In';
		$template->title .= $template->page_description;
		$template->set_focus = 'frmSignIn.txtUsernameOrEmailAddr';
		$content = ViewBuilder::factory('AppUserView')->sign_in($post_or_null, $errors);
		
		return $content;
		
	}
	
	public static function view_collections($accordions_open, $template, $post, $errors)
	{

		/*
		/	Purpose: Build View Collections page
		/
		/	Parms:
		/		'accordions_open' >> Array, indexed by view, indicating whether that view's accordion should default to open
		/		'template' >> Array containing page header attributes
		/		'post' >> Submitted form data or NULL
		/		'errors' >> Form validation errors or NULL
		/
		/	Returns:
		/		Page to render
		*/			
			
		// Read all collection types
		$collection_type_sel_arr = Model::factory('CollectionType')->read_all_collection_types();

		// Read user's collections
		$collection_data = array(
			'user_id' => Session::instance()->get('user_id')
			,'to_delete' => 0
		);
		$collection_arr = Model::factory('Collection')->read_one_user($collection_data);			
		
		// Render Collections page
		$template->page_description = 'Collections';
		$template->title .= $template->page_description;
		$template->navbar = View::factory('Navbar');
			  
		$collection_view = ViewBuilder::factory('CollectionView');
		
		// Add Collection view
		$content = $collection_view->add_collection($accordions_open['add_collection'], $collection_type_sel_arr, $post, $errors);
		
		// Collections view
		$content .= $collection_view->collections($accordions_open['collections'], $collection_arr);
		
		return $content;
		
	}	

} // End ViewBuilder_Page
