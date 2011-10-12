<?php

namespace Evolution\Bundles\Controller;
use \Evolution\Kernel;
use \Exception;

/**
 * Evolution Controller Bundle
 * @author Nate Ferrero
 */
class Bundle {
	
	private $controllers = array();
	
	public function route($dirs, $path) {
		
		// Make sure path contains valid controller name
		if(!isset($path[0]) || $path[0] == '')
			return;
		
		// Get the controller name
		$name = strtolower($path[0]);
		
		// Check all dirs for a matching controller
		foreach($dirs as $dir) {
			
			// Look in controllers folder
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
				$class = "\\Controllers\\$name\\Controller";
				
				// Check for valid class
				if(!class_exists($class))
					throw new Exception("Class `$class` is not defined in `$file`");
					
				// Load bundle
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