<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Template_Sitepage extends Controller_Template {

	// NOTE: This class will serve as the basis for all of this app's controllers,
	//	allowing us to construct all of the views, that the controllers render,
	//	from a common, base template (see ../application/views/Template/Sitepage.php)
	
	public $template = 'Template/Sitepage';
    
	public function before()
	{
 		
		/*
		/	Purpose: Add template controller default values for controllers that extend this class to override
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		parent::before();
		if ($this->auto_render) {            
			$this->template->title = 'Hector - ';
			$this->template->page_description = '';
			$this->template->navbar = '';
			$this->template->content = '';
			$this->template->styles = array();
			$this->template->scripts = array();
			$this->template->set_focus = '';
		}
		
	}

	public function after()
	{

		/*
		/	Purpose: Make styles and scripts available to controllers that extend this class
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		[NONE]
		*/

		if ($this->auto_render) {
			$styles = array(
				'media/css/normalize.css'
				,'media/css/foundation.min.css'
			);
			$scripts = array(
				'media/js/vendor/jquery.js'
				,'media/js/vendor/fastclick.js'
				,'media/js/foundation.min.js'
			);
			$this->template->styles = array_merge( $this->template->styles, $styles );
			$this->template->scripts = array_merge( $this->template->scripts, $scripts );
		}
		parent::after();
		
	}

} // End Template_SitePage
