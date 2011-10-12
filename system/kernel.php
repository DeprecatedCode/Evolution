<?php

namespace Evolution;
use \Exception;
use Evolution\Utility\JSON;

/**
 * Evolution Kernel
 * The kernel does only two things, load bundles and handle fatal exceptions
 * @author Nate Ferrero
 */
class Kernel {
	
	public static $root = null;
	
	public static $bundlePaths = null;
	
	public static $errorHandler;
	
	public static $exceptionHandler;
	
	// Private bundles list
	private static $bundles = array();
	
	// Handle Kernel::bundle() calls
	public static function __callStatic($name, $arguments) {
		
		// Bundles are never created twice
		$name = strtolower($name);
		if(!isset(self::$bundles[$name]))
			self::bundle($name);
		
		// Calling a bundle with arguments
		if(count($arguments) > 0) {
			return call_user_func_array(
				array(self::$bundles[$name], '__invoke_bundle'),
				$arguments
			);
		}
		
		// Otherwise, return the bundle
		return self::$bundles[$name];
	}
	
	// Load bundle paths
	private static function loadBundlePaths() {
		
		// Reset bundle paths
		self::$bundlePaths = array();
		
		// Look for bundles in these directories
		$searchLocations = array(
			self::$root . '/bundles'
		);
		
		// Check each location for bundles
		foreach($searchLocations as $location) {
			foreach(glob($location . '/*', GLOB_ONLYDIR) as $dir) {
				$name = basename($dir);
				
				// Add bundle to collection
				if(!isset(self::$bundlePaths[$name]))
					self::$bundlePaths[$name] = array();
					
				// Add path to collection
				self::$bundlePaths[$name][] = $dir;
			}
		}
	}
	
	// Load a bundle
	private static function bundle($name) {
		
		// Check if bundle paths have been loaded
		if(is_null(self::$bundlePaths))
			self::loadBundlePaths();
		
		// Check for bundle
		if(isset(self::$bundlePaths[$name])) {
			foreach(self::$bundlePaths[$name] as $dir) {
				
				// Check for bundle file
				$file = "$dir/_bundle.php";
				
				if(!is_file($file))
					continue;

				// load any dependencies
				if(is_file("$dir/dependencies.json")) {
					
					// make sure dependencies are also loaded
					$items = JSON::decodeFile("$dir/dependencies.json");

					foreach($items as $item) {
						self::$item();
					}
				}

				// load any plugins / extensions here so that the mixin bundle can properly mix our php object stack
					
				// Include the file
				require_once($file);
				
				// Bundle class
				$class = "\\Evolution\\Bundles\\$name\\Bundle";
				
				// Check for bundle class
				if(!class_exists($class))
					throw new Exception("Class `$class` is not defined in `$file`");
					
				// Load bundle
				self::$bundles[$name] = new $class;
				
				// initialize the bundle if wanted
				if(method_exists(self::$bundles[$name],'__system_initialize')) self::$bundles[$name]->__system_initialize();
				
				return true;
			}
		}
		
		// Not found
		throw new Exception("Bundle `$name` not found");
	}
}

// Save the Kernel root directory
Kernel::$root = dirname(__DIR__);

// Default Error Handler
Kernel::$errorHandler = function($errno , $errstr, $errfile, $errline) {
	throw new Exception("$errstr on line $errline of `$errfile`");
};

// Default Exception Handler
Kernel::$exceptionHandler = function($exception) {
	require(__DIR__ . '/exception.php');
};