<?php defined('SYSPATH') OR die('No direct script access.');

abstract class ViewBuilder {

	public static function factory($name)
	{
		
		/*
		/	Purpose: Abstract view builder class
		/
		/	Parms:
		/		[NONE]
		/		
		/	Returns:
		/		New instantiation of ViewBuilder class
		*/

		// Add the ViewBuilder prefix
		$class = 'ViewBuilder_'.$name;

		return new $class;
		
	}

}
