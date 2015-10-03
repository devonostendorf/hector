<?php defined('SYSPATH') or die('No direct script access.');

return array(

	// Group name, multiple configuration groups are supported
	'default' => array(

		// Multiple mechanisms can be added for versioned passwords, etc
		'mechanisms' => array(

			// Put your mechanisms here! The format is:
			// string $prefix => array(string $mechanism, array $config)

			// // bcrypt hashing using Blowfish encryption
			'bcrypt' => array('bcrypt', array(
			 	// number between 4 and 31, base-2 logarithm of the iteration count
			 	'cost' => 12
			)),
			
		),
	),
);
