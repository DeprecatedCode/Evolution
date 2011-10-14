<?php

namespace Evolution\Bundles\Controller;
use \Evolution\Kernel;
use \Evolution\Configure;
use \Exception;

/**
 * Standard configuration
 */
Configure::add('controller.class-format', '\\Controllers\\%\\Controller');

/**
 * Evolution Controller Bundle
 * @author Nate Ferrero
 */
class Bundle {
	
	private $controllers = array();
	
	public function route($path, $dirs = null) {
		
		// If dirs are not specified, use defaults
		if(is_null($dirs))
			$dirs = Configure::getArray('controller.location');
		
		// Make sure path contains valid controller name
		if(!isset($path[0]) || $path[0] == '')
			return;
		
		// Get the controller name
		$name = strtolower($path[0]);
		
		// Check all dirs for a matching controller
		foreach($dirs as $dir) {
			// Look in controllers folder
			if(basename($dir) !== 'controllers')
				$dir .= '/controllers';
			
			// Skip if missing
			if(!is_dir($dir))
				continue;
				
			// File to check
			$file = "$dir/$name.php";
			
			// Skip if incorrect file
			if(!is_file($file))
				continue;
			
			// Load controller if not already loaded
			if(!isset($this->controllers[$file])) {
				
				// Require the controller
				require_once($file);
				
				// Controller class
				$classFormats = Configure::getArray('controller.class-format');
				
				// Check each class format
				$found = false;
				foreach($classFormats as $format) {
					
					// Format class with controller name
					$class = str_replace("%", $name, $format);
					
					// Check if this is a valid class
					if(class_exists($class)) {
						$found = true;
						break;
					}
				}
				
				// Maybe we just ran out of formats to check
				if(!$found) {
					$classes = implode('`, `', $classFormats);
					$classes = str_replace('%', $name, $classes);
					throw new Exception("None of the possible controller classes: `$classes` are defined in `$file`");
				}
				
				// Load controller
				$this->controllers[$file] = new $class;
			}
			
			// Strip the controller name from the path
			array_shift($path);
			
			// Get the method name
			$method = array_shift($path);
			
			// Make sure path contains valid method name
			if(strlen($method) === 0)
				throw new Exception("No controller method specified when loading controller `$name`");
			
			// make sure that our controller method exists before attempting to call it
			if(!method_exists($this->controllers[$file],$method))			
				   throw new Exception("Controller `$name` exists but the method `$method` not specified");
	
			// Call the appropriate controller method with the remaining path elements as arguments
			$result = call_user_func_array(
				array($this->controllers[$file], $method),
				$path
			);
            
            // Complete the current binding queue
            Kernel::bindings()->complete($result);
		}
	}
	
}