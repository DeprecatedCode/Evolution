<?php

namespace Evolution;
use \Evolution\Kernel;
use \Exception;

/**
 * Evolution Configure
 * Configure sets basic evolution options, and provides a consistent way to start
 * evolution under any type of environment
 * @author Nate Ferrero
 */
class Configure {
	
	private static $configuration = array();
	
	public static function set($name, $value) {
		self::$configuration[$name] = $value;
	}
	
	public static function add($name, $value) {
		if(isset(self::$configuration[$name]) && 
			is_array(self::$configuration[$name]))
			self::$configuration[$name][] = $value;
		else if(!isset(self::$configuration[$name]))
			self::$configuration[$name] = array($value);
		else
			self::$configuration[$name] = array(self::$configuration[$name], $value);
	}
	
	public static function get($name) {
		return isset(self::$configuration[$name]) ? self::$configuration[$name] : null;
	}
	
	public static function getArray($name) {
		$x = self::get($name);
		if(is_array($x))
			return $x;
		if(is_null($x))
			return array();
		return array($x);
	}
	
	// Start evolution with given configuration
	public static function run() {
		
		// Root folder
		$root = dirname(__DIR__);

		// Change dir to keep evolution clean
		chdir("$root/tmp");
		
		// Include some basic utilities
		foreach(glob(__DIR__ . '/utilities/*.php') as $file)
		    require_once($file);
		
		// Include Evolution Kernel
		require_once(__DIR__ . '/kernel.php');
		
		// Handle errors
		set_error_handler(Kernel::$errorHandler);
		
		// Handle exceptions
		set_exception_handler(Kernel::$exceptionHandler);
		
		// Route the request
		Kernel::router()->route();
	}
}